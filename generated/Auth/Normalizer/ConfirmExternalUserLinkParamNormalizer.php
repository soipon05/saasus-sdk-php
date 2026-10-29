<?php

namespace AntiPatternInc\Saasus\Sdk\Auth\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use AntiPatternInc\Saasus\Sdk\Auth\Runtime\Normalizer\CheckArray;
use AntiPatternInc\Saasus\Sdk\Auth\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class ConfirmExternalUserLinkParamNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === \AntiPatternInc\Saasus\Sdk\Auth\Model\ConfirmExternalUserLinkParam::class;
    }
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === AntiPatternInc\Saasus\Sdk\Auth\Model\ConfirmExternalUserLinkParam::class;
    }
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (isset($data['$ref'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        $object = new \AntiPatternInc\Saasus\Sdk\Auth\Model\ConfirmExternalUserLinkParam();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (\array_key_exists('access_token', $data) && $data['access_token'] !== null) {
            $object->setAccessToken($data['access_token']);
            unset($data['access_token']);
        }
        elseif (\array_key_exists('access_token', $data) && $data['access_token'] === null) {
            $object->setAccessToken(null);
        }
        if (\array_key_exists('code', $data) && $data['code'] !== null) {
            $object->setCode($data['code']);
            unset($data['code']);
        }
        elseif (\array_key_exists('code', $data) && $data['code'] === null) {
            $object->setCode(null);
        }
        foreach ($data as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value;
            }
        }
        return $object;
    }
    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $data = [];
        $data['access_token'] = $object->getAccessToken();
        $data['code'] = $object->getCode();
        foreach ($object as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $data[$key] = $value;
            }
        }
        return $data;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\AntiPatternInc\Saasus\Sdk\Auth\Model\ConfirmExternalUserLinkParam::class => false];
    }
}
