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
use Metadata\Driver\FileLocatorInterface;


/**
 * Class FileLocatorAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\MetadataDriver
 */
class FileLocatorAbstractFactory implements AbstractFactoryInterface
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
        return 0 === strpos($requestedName, 'nnxJmsSerializer.fileLocators.');
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

        $fileLocatorName = substr($requestedName, 30);

        /** @var  ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);

        $fileLocatorConfig = $moduleOptions->getFileLocator($fileLocatorName);

        $name = $fileLocatorConfig->getName();
        $options = $fileLocatorConfig->getOptions();

        $fileLocator =  $serviceLocator->get(
            $name,
            $options
        );

        if (!$fileLocator instanceof FileLocatorInterface) {
            $errMsg = sprintf(
                'File locator of type %s is invalid; must implement %s',
                (is_object($fileLocator) ? get_class($fileLocator) : gettype($fileLocator)),
                FileLocatorInterface::class
            );
            throw new Exception\RuntimeException($errMsg);
        }

        return $fileLocator;
    }
}
