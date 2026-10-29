<?php

namespace AntiPatternInc\Saasus\Sdk\AwsMarketplace\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use AntiPatternInc\Saasus\Sdk\AwsMarketplace\Runtime\Normalizer\CheckArray;
use AntiPatternInc\Saasus\Sdk\AwsMarketplace\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class CloudFormationLaunchStackLinkNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === \AntiPatternInc\Saasus\Sdk\AwsMarketplace\Model\CloudFormationLaunchStackLink::class;
    }
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === AntiPatternInc\Saasus\Sdk\AwsMarketplace\Model\CloudFormationLaunchStackLink::class;
    }
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (isset($data['$ref'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        $object = new \AntiPatternInc\Saasus\Sdk\AwsMarketplace\Model\CloudFormationLaunchStackLink();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (\array_key_exists('link', $data) && $data['link'] !== null) {
            $object->setLink($data['link']);
            unset($data['link']);
        }
        elseif (\array_key_exists('link', $data) && $data['link'] === null) {
            $object->setLink(null);
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
        $data['link'] = $object->getLink();
        foreach ($object as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $data[$key] = $value;
            }
        }
        return $data;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\AntiPatternInc\Saasus\Sdk\AwsMarketplace\Model\CloudFormationLaunchStackLink::class => false];
    }
}
