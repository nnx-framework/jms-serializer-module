<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Serializer;

use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\Serializer\Adapter\AdapterInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\JmsSerializerModule\Options\ModuleOptions;

/**
 * Class JmsSerializerAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\Serializer
 */
class JmsSerializerAbstractFactory implements AbstractFactoryInterface
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
        return 0 === strpos($requestedName, 'nnxJmsSerializer.serializers.');
    }

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return AdapterInterface
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $jmsSerializerName = substr($requestedName, 29);

        /** @var  ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);

        $serializerConfig = $moduleOptions->getSerializer($jmsSerializerName);

        $options = $serializerConfig->getOptions();
        $serializer = $serviceLocator->get(
            $serializerConfig->getName(),
            [
                'metadataFactory' => $options->getMetadataFactory(),
                'handlerRegistry' => $options->getHandlerRegistry(),
                'objectConstructor' => $options->getObjectConstructor(),
                'serializationVisitors' => $options->getSerializationVisitors(),
                'deserializationVisitors' => $options->getDeserializationVisitors(),
                'eventDispatcher'  => $options->getEventDispatcher(),
                'typeParser' => $options->getTypeParser()
            ]
        );


        return $serializer;
    }
}
