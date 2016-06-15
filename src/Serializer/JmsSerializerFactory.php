<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Serializer;

use Nnx\JmsSerializerModule\ModuleServiceManager\JmsSerializerModuleServiceManager;
use Nnx\JmsSerializerModule\Options\SerializerOptions;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Metadata\MetadataFactoryInterface;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Construction\ObjectConstructorInterface;
use PhpCollection\MapInterface;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;

/**
 * Class JmsSerializerFactory
 *
 * @package Nnx\JmsSerializerModule\Serializer
 */
class JmsSerializerFactory  implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return JmsSerializer
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $creationOptions = $this->getCreationOptions();

        $options = new SerializerOptions($creationOptions);



        /** @var JmsSerializerModuleServiceManager $jmsSerializerModuleServiceManager */
        $jmsSerializerModuleServiceManager = $appServiceLocator->get(JmsSerializerModuleServiceManager::class);

        $metadataFactoryName = $options->getMetadataFactory();
        /** @var MetadataFactoryInterface $metadataFactory */
        $metadataFactory = $jmsSerializerModuleServiceManager->get($metadataFactoryName);

        $handlerRegistryName = $options->getHandlerRegistry();
        /** @var HandlerRegistryInterface $handlerRegistry */
        $handlerRegistry = $jmsSerializerModuleServiceManager->get($handlerRegistryName);

        $objectConstructorName = $options->getObjectConstructor();
        /** @var ObjectConstructorInterface $objectConstructor */
        $objectConstructor = $jmsSerializerModuleServiceManager->get($objectConstructorName);

        $serializationVisitorsServiceName = $options->getSerializationVisitors();
        /** @var MapInterface $serializationVisitors */
        $serializationVisitors = $jmsSerializerModuleServiceManager->get($serializationVisitorsServiceName);

        $deserializationVisitorsServiceName = $options->getDeserializationVisitors();
        /** @var MapInterface $deserializationVisitors */
        $deserializationVisitors = $jmsSerializerModuleServiceManager->get($deserializationVisitorsServiceName);

        $eventDispatcherName = $options->getEventDispatcher();
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $jmsSerializerModuleServiceManager->get($eventDispatcherName);

        return new JmsSerializer(
            $metadataFactory,
            $handlerRegistry,
            $objectConstructor,
            $serializationVisitors,
            $deserializationVisitors,
            $eventDispatcher
        );
    }
}
