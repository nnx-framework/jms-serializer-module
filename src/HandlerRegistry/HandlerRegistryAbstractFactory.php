<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\HandlerRegistry;

use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\JmsSerializerModule\Options\ModuleOptions;
use JMS\Serializer\Handler\HandlerRegistryInterface;

/**
 * Class HandlerRegistryAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\HandlerRegistry
 */
class HandlerRegistryAbstractFactory implements AbstractFactoryInterface
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
        return 0 === strpos($requestedName, 'nnxJmsSerializer.handlerRegistry.');
    }

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return HandlerRegistryInterface
     * @throws \Nnx\JmsSerializerModule\HandlerRegistry\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\MetadataDriver\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $handlerRegistryName = substr($requestedName, 33);

        /** @var  ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);

        $handlerRegistryConfig = $moduleOptions->getHandlerRegistry($handlerRegistryName);

        $name = $handlerRegistryConfig->getName();
        $options = $handlerRegistryConfig->getOptions();

        $handlerRegistry =  $serviceLocator->get(
            $name,
            $options
        );

        if (!$handlerRegistry instanceof HandlerRegistryInterface) {
            $errMsg = sprintf(
                'Handler registry of type %s is invalid; must implement %s',
                (is_object($handlerRegistry) ? get_class($handlerRegistry) : gettype($handlerRegistry)),
                HandlerRegistryInterface::class
            );
            throw new Exception\RuntimeException($errMsg);
        }

        return $handlerRegistry;
    }
}
