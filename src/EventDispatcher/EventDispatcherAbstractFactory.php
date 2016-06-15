<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\EventDispatcher;

use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\JmsSerializerModule\Options\ModuleOptions;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;


/**
 * Class EventDispatcherAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\EventDispatcher
 */
class EventDispatcherAbstractFactory implements AbstractFactoryInterface
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
        return 0 === strpos($requestedName, 'nnxJmsSerializer.eventDispatchers.');
    }

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return EventDispatcherInterface
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     * @throws \Nnx\JmsSerializerModule\EventDispatcher\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $eventDispatcherName = substr($requestedName, 34);

        /** @var  ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);

        $eventDispatcherConfig = $moduleOptions->getEventDispatcher($eventDispatcherName);

        $name = $eventDispatcherConfig->getName();
        $options = $eventDispatcherConfig->getOptions();

        $eventDispatcher =  $serviceLocator->get(
            $name,
            $options
        );

        if (!$eventDispatcher instanceof EventDispatcherInterface) {
            $errMsg = sprintf(
                'Object constructor of type %s is invalid; must implement %s',
                (is_object($eventDispatcher) ? get_class($eventDispatcher) : gettype($eventDispatcher)),
                EventDispatcherInterface::class
            );
            throw new Exception\RuntimeException($errMsg);
        }

        return $eventDispatcher;
    }
}
