<?php

namespace AntiPatternInc\Saasus\Sdk\ApiLog\Normalizer;

use AntiPatternInc\Saasus\Sdk\ApiLog\Runtime\Normalizer\CheckArray;
use AntiPatternInc\Saasus\Sdk\ApiLog\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class JaneObjectNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    protected $normalizers = [
        
        \AntiPatternInc\Saasus\Sdk\ApiLog\Model\Error::class => \AntiPatternInc\Saasus\Sdk\ApiLog\Normalizer\ErrorNormalizer::class,
        
        \AntiPatternInc\Saasus\Sdk\ApiLog\Model\ApiLogs::class => \AntiPatternInc\Saasus\Sdk\ApiLog\Normalizer\ApiLogsNormalizer::class,
        
        \Jane\Component\JsonSchemaRuntime\Reference::class => \AntiPatternInc\Saasus\Sdk\ApiLog\Runtime\Normalizer\ReferenceNormalizer::class,
    ], $normalizersCache = [];
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return array_key_exists($type, $this->normalizers);
    }
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return is_object($data) && array_key_exists(get_class($data), $this->normalizers);
    }
    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $normalizerClass = $this->normalizers[get_class($object)];
        $normalizer = $this->getNormalizer($normalizerClass);
        return $normalizer->normalize($object, $format, $context);
    }
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $denormalizerClass = $this->normalizers[$type];
        $denormalizer = $this->getNormalizer($denormalizerClass);
        return $denormalizer->denormalize($data, $type, $format, $context);
    }
    private function getNormalizer(string $normalizerClass)
    {
        return $this->normalizersCache[$normalizerClass] ?? $this->initNormalizer($normalizerClass);
    }
    private function initNormalizer(string $normalizerClass)
    {
        $normalizer = new $normalizerClass();
        $normalizer->setNormalizer($this->normalizer);
        $normalizer->setDenormalizer($this->denormalizer);
        $this->normalizersCache[$normalizerClass] = $normalizer;
        return $normalizer;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\AntiPatternInc\Saasus\Sdk\ApiLog\Model\Error::class => false, \AntiPatternInc\Saasus\Sdk\ApiLog\Model\ApiLogs::class => false, \Jane\Component\JsonSchemaRuntime\Reference::class => false];
    }
}
