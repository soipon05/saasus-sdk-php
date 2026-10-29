<?php

namespace AntiPatternInc\Saasus\Sdk\Integration\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use AntiPatternInc\Saasus\Sdk\Integration\Runtime\Normalizer\CheckArray;
use AntiPatternInc\Saasus\Sdk\Integration\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class CreateEventBridgeEventParamNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === \AntiPatternInc\Saasus\Sdk\Integration\Model\CreateEventBridgeEventParam::class;
    }
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === AntiPatternInc\Saasus\Sdk\Integration\Model\CreateEventBridgeEventParam::class;
    }
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (isset($data['$ref'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        $object = new \AntiPatternInc\Saasus\Sdk\Integration\Model\CreateEventBridgeEventParam();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (\array_key_exists('event_messages', $data)) {
            $values = [];
            foreach ($data['event_messages'] as $value) {
                $values[] = $this->denormalizer->denormalize($value, \AntiPatternInc\Saasus\Sdk\Integration\Model\EventMessage::class, 'json', $context);
            }
            $object->setEventMessages($values);
            unset($data['event_messages']);
        }
        foreach ($data as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $object[$key] = $value_1;
            }
        }
        return $object;
    }
    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $data = [];
        $values = [];
        foreach ($object->getEventMessages() as $value) {
            $values[] = $this->normalizer->normalize($value, 'json', $context);
        }
        $data['event_messages'] = $values;
        foreach ($object as $key => $value_1) {
            if (preg_match('/.*/', (string) $key)) {
                $data[$key] = $value_1;
            }
        }
        return $data;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\AntiPatternInc\Saasus\Sdk\Integration\Model\CreateEventBridgeEventParam::class => false];
    }
}
