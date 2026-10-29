<?php

declare(strict_types=1);

/**
 * Mechanical, idempotent post-processing of the Jane-generated code under generated/
 * so it runs warning-free on PHP 8.4 / 8.5 with symfony/serializer >= 6.4.
 *
 * Usage: php tools/fix-generated.php [--only=fix1,fix2] [generated-dir]   (re-run after every regeneration)
 *
 * Fixes, in order:
 *  1. kernel-branch  Drop `if (... Kernel::MAJOR_VERSION ...) { A } else { B }` wrappers, keeping A
 *                    (the symfony/serializer >= 6.4 variant; composer.json conflicts with <6.4).
 *  2. reference      ReferenceNormalizer: current NormalizerInterface signatures + getSupportedTypes().
 *  3. nullable       Implicitly nullable parameters (`T $x = null`) -> `?T $x = null`.
 *  4. double-cast    `(double)` -> `(float)`.
 *  5. fqcn           Unqualified `AntiPatternInc\...` names inside a namespace -> `\AntiPatternInc\...`
 *                    (they were resolved relative to the current namespace, i.e. a non-existent class).
 *  6. fetch-response Runtime Client: drop the trigger_deprecation() for FETCH_RESPONSE; behaviour is
 *                    unchanged (it still returns executeRawEndpoint()).
 */

$args = array_slice($argv, 1);
$only = null;
foreach ($args as $k => $a) {
    if (str_starts_with($a, '--only=')) {
        $only = explode(',', substr($a, 7));
        unset($args[$k]);
    }
}
$dir = rtrim(array_values($args)[0] ?? dirname(__DIR__) . '/generated', '/');
$GLOBALS['enabled'] = static fn (string $fix): bool => $only === null || in_array($fix, $only, true);
$stats = array_fill_keys(['kernel-branch', 'reference', 'nullable', 'double-cast', 'fqcn', 'fetch-response', 'files-changed'], 0);

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
$files = [];
foreach ($it as $f) {
    if ($f->getExtension() === 'php') {
        $files[] = $f->getPathname();
    }
}
sort($files);

foreach ($files as $path) {
    $orig = file_get_contents($path);
    $src = $orig;
    $on = $GLOBALS['enabled'];
    if ($on('kernel-branch')) {
        $src = fixKernelBranch($src, $stats);
    }
    if ($on('reference') && basename($path) === 'ReferenceNormalizer.php') {
        $src = fixReferenceNormalizer($src, $stats);
    }
    if ($on('fetch-response') && str_ends_with($path, '/Runtime/Client/Client.php')) {
        $src = fixFetchResponse($src, $stats);
    }
    $src = fixTokens($src, $stats);
    if ($src !== $orig) {
        file_put_contents($path, $src);
        $stats['files-changed']++;
    }
}

foreach ($stats as $k => $v) {
    printf("%-15s %d\n", $k, $v);
}

function fixKernelBranch(string $src, array &$stats): string
{
    $lines = explode("\n", $src);
    $start = null;
    foreach ($lines as $i => $l) {
        if (preg_match('/^if \(.*Kernel::(MAJOR_VERSION|class).*\) \{$/', $l)) {
            $start = $i;
            break;
        }
    }
    if ($start === null) {
        return $src;
    }
    $else = null;
    $end = null;
    for ($i = $start + 1, $n = count($lines); $i < $n; $i++) {
        if ($lines[$i] === '} else {' && $else === null) {
            $else = $i;
        } elseif ($lines[$i] === '}') {
            $end = $i;
        }
    }
    if ($else === null || $end === null || $end < $else) {
        fwrite(STDERR, "kernel-branch: unexpected layout, skipped\n");
        return $src;
    }
    $body = array_map(
        static fn (string $l): string => str_starts_with($l, '    ') ? substr($l, 4) : $l,
        array_slice($lines, $start + 1, $else - $start - 1)
    );
    $out = array_merge(array_slice($lines, 0, $start), $body, array_slice($lines, $end + 1));
    $out = array_values(array_filter($out, static fn (string $l): bool => $l !== 'use Symfony\Component\HttpKernel\Kernel;'));
    $stats['kernel-branch']++;
    $src = implode("\n", $out);

    return str_ends_with($src, "\n") ? $src : $src . "\n";
}

function fixReferenceNormalizer(string $src, array &$stats): string
{
    $old = 'public function supportsNormalization($data, $format = null): bool';
    if (!str_contains($src, $old)) {
        return $src;
    }
    $src = str_replace($old, 'public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool', $src);
    $method = <<<'PHP'
        public function getSupportedTypes(?string $format): array
        {
            return [Reference::class => true];
        }

    PHP;
    // append before the closing brace of the class (last "}" line)
    $pos = strrpos(rtrim($src), '}');
    $src = substr($src, 0, $pos) . $method . "}\n";
    $stats['reference']++;

    return $src;
}

function fixFetchResponse(string $src, array &$stats): string
{
    $new = preg_replace('/^\s*trigger_deprecation\(\'jane-php\/open-api-common\', \'7\.3\', \'Using %s::%s method with \$fetch parameter equals to response is deprecated.*\n/m', '', $src, -1, $c);
    $stats['fetch-response'] += $c;

    return $new;
}

/** Token-based fixes: implicit nullable, (double), relative AntiPatternInc names. */
function fixTokens(string $src, array &$stats): string
{
    $on = $GLOBALS['enabled'];
    $tokens = PhpToken::tokenize($src);
    $n = count($tokens);
    $hasNamespace = false;
    $skipUntilSemicolon = false;

    for ($i = 0; $i < $n; $i++) {
        $t = $tokens[$i];

        if ($on('double-cast') && $t->is(T_DOUBLE_CAST) && strtolower(str_replace([' ', "\t"], '', $t->text)) === '(double)') {
            $t->text = '(float)';
            $stats['double-cast']++;
            continue;
        }

        if ($t->is(T_NAMESPACE)) {
            $hasNamespace = true;
            $skipUntilSemicolon = true;
            continue;
        }
        if ($t->is(T_USE)) {
            // namespace import / trait use (closure `use (` is not followed by a name)
            $j = nextSignificant($tokens, $i);
            if ($j !== null && $tokens[$j]->text !== '(') {
                $skipUntilSemicolon = true;
            }
            continue;
        }
        if ($skipUntilSemicolon) {
            if ($t->text === ';' || $t->text === '{') {
                $skipUntilSemicolon = false;
            }
            continue;
        }
        if ($on('fqcn') && $hasNamespace && $t->is(T_NAME_QUALIFIED) && str_starts_with($t->text, 'AntiPatternInc\\')) {
            $t->text = '\\' . $t->text;
            $stats['fqcn']++;
            continue;
        }

        if ($on('nullable') && ($t->is(T_FUNCTION) || $t->is(T_FN))) {
            $i = fixParams($tokens, $i, $stats);
        }
    }

    return implode('', array_map(static fn (PhpToken $t): string => $t->text, $tokens));
}

function nextSignificant(array $tokens, int $i): ?int
{
    for ($j = $i + 1, $n = count($tokens); $j < $n; $j++) {
        if (!$tokens[$j]->isIgnorable()) {
            return $j;
        }
    }

    return null;
}

/** Walk one parameter list starting at a function token; returns index of the closing ")". */
function fixParams(array $tokens, int $i, array &$stats): int
{
    $n = count($tokens);
    while ($i < $n && $tokens[$i]->text !== '(') {
        $i++;
    }
    $open = $i;
    $depth = 0;
    $params = [];
    $cur = [];
    for ($i = $open; $i < $n; $i++) {
        $txt = $tokens[$i]->text;
        if ($txt === '(' || $txt === '[' || $txt === '{') {
            $depth++;
            if ($depth === 1) {
                continue;
            }
        } elseif ($txt === ')' || $txt === ']' || $txt === '}') {
            $depth--;
            if ($depth === 0) {
                if ($cur) {
                    $params[] = $cur;
                }
                break;
            }
        } elseif ($txt === ',' && $depth === 1) {
            $params[] = $cur;
            $cur = [];
            continue;
        }
        $cur[] = $i;
    }
    $close = $i;

    foreach ($params as $idx) {
        $sig = array_values(array_filter($idx, static fn (int $k): bool => !$tokens[$k]->isIgnorable()));
        $var = null;
        foreach ($sig as $p => $k) {
            if ($tokens[$k]->is(T_VARIABLE)) {
                $var = $p;
                break;
            }
        }
        if ($var === null) {
            continue;
        }
        // default must be exactly `= null`
        $rest = array_slice($sig, $var + 1);
        if (count($rest) !== 2 || $tokens[$rest[0]]->text !== '=' || strtolower($tokens[$rest[1]]->text) !== 'null') {
            continue;
        }
        // type tokens: everything before the variable except modifiers, & and ...
        $typeIdx = array_values(array_filter(
            array_slice($sig, 0, $var),
            static fn (int $k): bool => !$tokens[$k]->is([T_PUBLIC, T_PROTECTED, T_PRIVATE, T_READONLY, T_ELLIPSIS])
                && $tokens[$k]->text !== '&'
                && !$tokens[$k]->is(T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG)
                && !$tokens[$k]->is(T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG)
        ));
        if (!$typeIdx) {
            continue;
        }
        $type = strtolower(implode('', array_map(static fn (int $k): string => $tokens[$k]->text, $typeIdx)));
        if ($type === 'mixed' || $type === 'null' || str_starts_with($type, '?') || preg_match('/(^|\|)null(\||$)/', $type)) {
            continue;
        }
        if (str_contains($type, '|')) {
            $tokens[end($typeIdx)]->text .= '|null';
        } elseif (str_contains($type, '&')) {
            continue; // intersection types cannot be made nullable without DNF parentheses; none in generated code
        } else {
            $tokens[$typeIdx[0]]->text = '?' . $tokens[$typeIdx[0]]->text;
        }
        $stats['nullable']++;
    }

    return $close;
}
