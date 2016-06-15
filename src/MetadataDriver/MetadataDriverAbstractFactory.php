<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\MetadataDriver;

use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\Serializer\Adapter\AdapterInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\JmsSerializerModule\Options\ModuleOptions;
use Metadata\Driver\DriverInterface;


/**
 * Class MetadataDriverAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\MetadataDriver
 */
class MetadataDriverAbstractFactory implements AbstractFactoryInterface
{
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
        return 0 === strpos($requestedName, 'nnxJmsSerializer.metadataDriver.');
    }

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return AdapterInterface
     * @throws \Nnx\JmsSerializerModule\MetadataDriver\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $metadataDriverName = substr($requestedName, 32);

        /** @var  ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);

        $metadataDriverConfig = $moduleOptions->getMetadataDriver($metadataDriverName);

        $name = $metadataDriverConfig->getName();
        $options = $metadataDriverConfig->getOptions();

        $metadataDriver =  $serviceLocator->get(
            $name,
            $options
        );

        if (!$metadataDriver instanceof DriverInterface) {
            $errMsg = sprintf('Metadata driver not implement %s', DriverInterface::class);
            throw new Exception\RuntimeException($errMsg);
        }

        return $metadataDriver;
    }
}
