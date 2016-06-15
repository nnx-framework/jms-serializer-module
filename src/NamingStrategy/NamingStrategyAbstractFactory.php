<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\NamingStrategy;

use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\JmsSerializerModule\Options\ModuleOptions;

/**
 * Class NamingStrategyAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\NamingStrategy
 */
class NamingStrategyAbstractFactory implements AbstractFactoryInterface
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
        return 0 === strpos($requestedName, 'nnxJmsSerializer.namingStrategies.');
    }

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return PropertyNamingStrategyInterface
     * @throws \Nnx\JmsSerializerModule\NamingStrategy\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $strategyName = substr($requestedName, 34);

        /** @var  ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);

        $strategyConfig = $moduleOptions->getNamingStrategy($strategyName);

        $name = $strategyConfig->getName();
        $options = $strategyConfig->getOptions();

        $strategy =  $serviceLocator->get(
            $name,
            $options
        );

        if (!$strategy instanceof PropertyNamingStrategyInterface) {
            $errMsg = sprintf(
                'Naming strategy of type %s is invalid; must implement %s',
                (is_object($strategy) ? get_class($strategy) : gettype($strategy)),
                PropertyNamingStrategyInterface::class
            );
            throw new Exception\RuntimeException($errMsg);
        }

        return $strategy;
    }
}
