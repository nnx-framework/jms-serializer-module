<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\MetadataFactory;

use Nnx\JmsSerializerModule\Options\ModuleOptions;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use ReflectionClass;
use Metadata\Driver\DriverInterface;

/**
 * Class MetadataAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\MetadataFactory
 */
class MetadataAbstractFactory implements AbstractFactoryInterface
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
        return 0 === strpos($requestedName, 'nnxJmsSerializer.metadataFactory.');
    }

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $metadataFactoryName = substr($requestedName, 33);

        /** @var  ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);

        $metadataFactoryConfig = $moduleOptions->gasMetadataFactory($metadataFactoryName);

        $factoryClassName = $metadataFactoryConfig->getName();
        $metadataFactoryOptions = $metadataFactoryConfig->getOptions();

        $metadataDriverName = $metadataFactoryOptions->getMetadataDriver();

        /** @var DriverInterface $metadataDriver */
        $metadataDriver = $serviceLocator->get($metadataDriverName);

        $hierarchyMetadataClass = $metadataFactoryOptions->getHierarchyMetadataClass();
        $debug = $metadataFactoryOptions->getDebug();

        $r = new ReflectionClass($factoryClassName);
        return $r->newInstance(
            $metadataDriver,
            $hierarchyMetadataClass,
            $debug
        );
    }
}
