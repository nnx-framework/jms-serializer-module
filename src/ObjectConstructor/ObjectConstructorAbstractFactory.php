<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\ObjectConstructor;

use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\JmsSerializerModule\Options\ModuleOptions;
use JMS\Serializer\Construction\ObjectConstructorInterface;


/**
 * Class ObjectConstructorAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\ObjectConstructor
 */
class ObjectConstructorAbstractFactory implements AbstractFactoryInterface
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
        return 0 === strpos($requestedName, 'nnxJmsSerializer.objectConstructor.');
    }

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return ObjectConstructorInterface
     * @throws \Nnx\JmsSerializerModule\ObjectConstructor\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $objectConstructorName = substr($requestedName, 35);

        /** @var  ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);

        $objectConstructorConfig = $moduleOptions->getObjectConstructor($objectConstructorName);

        $name = $objectConstructorConfig->getName();
        $options = $objectConstructorConfig->getOptions();

        $objectConstructor =  $serviceLocator->get(
            $name,
            $options
        );

        if (!$objectConstructor instanceof ObjectConstructorInterface) {
            $errMsg = sprintf(
                'Object constructor of type %s is invalid; must implement %s',
                (is_object($objectConstructor) ? get_class($objectConstructor) : gettype($objectConstructor)),
                ObjectConstructorInterface::class
            );
            throw new Exception\RuntimeException($errMsg);
        }

        return $objectConstructor;
    }
}
