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
class SingleTenantSettingsNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === \AntiPatternInc\Saasus\Sdk\Auth\Model\SingleTenantSettings::class;
    }
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === AntiPatternInc\Saasus\Sdk\Auth\Model\SingleTenantSettings::class;
    }
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (isset($data['$ref'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        $object = new \AntiPatternInc\Saasus\Sdk\Auth\Model\SingleTenantSettings();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (\array_key_exists('enabled', $data) && $data['enabled'] !== null) {
            $object->setEnabled($data['enabled']);
            unset($data['enabled']);
        }
        elseif (\array_key_exists('enabled', $data) && $data['enabled'] === null) {
            $object->setEnabled(null);
        }
        if (\array_key_exists('role_arn', $data) && $data['role_arn'] !== null) {
            $object->setRoleArn($data['role_arn']);
            unset($data['role_arn']);
        }
        elseif (\array_key_exists('role_arn', $data) && $data['role_arn'] === null) {
            $object->setRoleArn(null);
        }
        if (\array_key_exists('cloudformation_template_url', $data) && $data['cloudformation_template_url'] !== null) {
            $object->setCloudformationTemplateUrl($data['cloudformation_template_url']);
            unset($data['cloudformation_template_url']);
        }
        elseif (\array_key_exists('cloudformation_template_url', $data) && $data['cloudformation_template_url'] === null) {
            $object->setCloudformationTemplateUrl(null);
        }
        if (\array_key_exists('ddl_template_url', $data) && $data['ddl_template_url'] !== null) {
            $object->setDdlTemplateUrl($data['ddl_template_url']);
            unset($data['ddl_template_url']);
        }
        elseif (\array_key_exists('ddl_template_url', $data) && $data['ddl_template_url'] === null) {
            $object->setDdlTemplateUrl(null);
        }
        if (\array_key_exists('role_external_id', $data) && $data['role_external_id'] !== null) {
            $object->setRoleExternalId($data['role_external_id']);
            unset($data['role_external_id']);
        }
        elseif (\array_key_exists('role_external_id', $data) && $data['role_external_id'] === null) {
            $object->setRoleExternalId(null);
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
        $data['enabled'] = $object->getEnabled();
        $data['role_arn'] = $object->getRoleArn();
        $data['cloudformation_template_url'] = $object->getCloudformationTemplateUrl();
        $data['ddl_template_url'] = $object->getDdlTemplateUrl();
        $data['role_external_id'] = $object->getRoleExternalId();
        foreach ($object as $key => $value) {
            if (preg_match('/.*/', (string) $key)) {
                $data[$key] = $value;
            }
        }
        return $data;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\AntiPatternInc\Saasus\Sdk\Auth\Model\SingleTenantSettings::class => false];
    }
}
