<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Nnx\JmsSerializerModule\Options\ModuleOptions;
use Interop\Container\ContainerInterface;

/**
 * Class ImportEngineFactory
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
class ImportEngineFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager */
        $moduleOptionsPluginManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsPluginManager->get(ModuleOptions::class);

        $entityLocatorServiceName = $moduleOptions->getEntityLocator();

        /** @var ContainerInterface $entityLocator */
        $entityLocator = $serviceLocator->get($entityLocatorServiceName);

        return new ImportEngine($entityLocator);
    }
}
