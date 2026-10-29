<?php

namespace AntiPatternInc\Saasus\Sdk\Pricing\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use AntiPatternInc\Saasus\Sdk\Pricing\Runtime\Normalizer\CheckArray;
use AntiPatternInc\Saasus\Sdk\Pricing\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class PricingTierNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === \AntiPatternInc\Saasus\Sdk\Pricing\Model\PricingTier::class;
    }
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === AntiPatternInc\Saasus\Sdk\Pricing\Model\PricingTier::class;
    }
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (isset($data['$ref'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        $object = new \AntiPatternInc\Saasus\Sdk\Pricing\Model\PricingTier();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (\array_key_exists('up_to', $data) && $data['up_to'] !== null) {
            $object->setUpTo($data['up_to']);
            unset($data['up_to']);
        }
        elseif (\array_key_exists('up_to', $data) && $data['up_to'] === null) {
            $object->setUpTo(null);
        }
        if (\array_key_exists('unit_amount', $data) && $data['unit_amount'] !== null) {
            $object->setUnitAmount($data['unit_amount']);
            unset($data['unit_amount']);
        }
        elseif (\array_key_exists('unit_amount', $data) && $data['unit_amount'] === null) {
            $object->setUnitAmount(null);
        }
        if (\array_key_exists('flat_amount', $data) && $data['flat_amount'] !== null) {
            $object->setFlatAmount($data['flat_amount']);
            unset($data['flat_amount']);
        }
        elseif (\array_key_exists('flat_amount', $data) && $data['flat_amount'] === null) {
            $object->setFlatAmount(null);
        }
        if (\array_key_exists('inf', $data) && $data['inf'] !== null) {
            $object->setInf($data['inf']);
            unset($data['inf']);
        }
        elseif (\array_key_exists('inf', $data) && $data['inf'] === null) {
            $object->setInf(null);
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
        $data['up_to'] = $object->getUpTo();
        $data['unit_amount'] = $object->getUnitAmount();
        $data['flat_amount'] = $object->getFlatAmount();
        $data['inf'] = $object->getInf();
        foreach ($object as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $data[$key] = $value;
            }
        }
        return $data;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\AntiPatternInc\Saasus\Sdk\Pricing\Model\PricingTier::class => false];
    }
}
