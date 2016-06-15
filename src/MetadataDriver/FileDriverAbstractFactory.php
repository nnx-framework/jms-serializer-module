<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\MetadataDriver;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use ReflectionClass;
use Metadata\Driver\AbstractFileDriver;

/**
 * Class FileDriverAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\MetadataDriver
 */
class FileDriverAbstractFactory implements AbstractFactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool|void
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return class_exists($requestedName) && is_subclass_of($requestedName, AbstractFileDriver::class);
    }

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return AbstractFileDriver
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\MetadataDriver\Exception\RuntimeException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $creationOptions = $this->getCreationOptions();

        if (!array_key_exists('fileLocator', $creationOptions)) {
            $errMsg = 'File locator name not specified';
            throw new Exception\RuntimeException($errMsg);
        }

        $fileLocatorName = $creationOptions['fileLocator'];
        $fileLocator = $serviceLocator->get($fileLocatorName);

        $r = new ReflectionClass($requestedName);
        return $r->newInstance($fileLocator);
    }
}
