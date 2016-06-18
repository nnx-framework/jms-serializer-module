<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule;

use Nnx\JmsSerializerModule\MetadataFactory;
use Nnx\JmsSerializerModule\MetadataDriver;
use Metadata\Driver\DriverChain;
use Nnx\JmsSerializerModule\MetadataReader;
use JMS\Serializer\Metadata\Driver as JmsSerializerMetadataDriver;
use Metadata\Driver\FileLocator;
use Nnx\JmsSerializerModule\HandlerRegistry;
use JMS\Serializer\Handler\HandlerRegistry as JmsHandlerRegistry;
use Nnx\JmsSerializerModule\ObjectConstructor;
use JMS\Serializer\Construction;
use Doctrine\Common\Persistence\ManagerRegistry;
use Nnx\JmsSerializerModule\Util;
use Nnx\JmsSerializerModule\Visitor;
use JMS\Serializer;
use Nnx\JmsSerializerModule\NamingStrategy;
use JMS\Serializer\Naming as JmsNamingStrategy;
use Nnx\JmsSerializerModule\EventDispatcher;
use JMS\Serializer\EventDispatcher\EventDispatcher as JmsEventDispatcher;
use JMS\Serializer\Handler;
use JMS\Serializer\EventDispatcher\Subscriber\DoctrineProxySubscriber;
use Nnx\JmsSerializerModule\DataContainerBuilder;
use Nnx\JmsSerializerModule\DoctrineObjectEngine;
use Nnx\JmsSerializerModule\ObjectConstructor\DoctrineObjectConstructor\DataInterface as DoctrineObjectConstructorData;
use Nnx\JmsSerializerModule\ObjectConstructor\DoctrineObjectConstructor\DataFactory as DoctrineObjectConstructorDataFactory;


return [
    Module::MODULE_SERVICE_MANAGER_CONFIG_KEY => [
        'invokables'         => [
            Construction\UnserializeObjectConstructor::class => Construction\UnserializeObjectConstructor::class,
            Handler\ArrayCollectionHandler::class            => Handler\ArrayCollectionHandler::class,
            Handler\DateHandler::class                       => Handler\DateHandler::class,
            Handler\PhpCollectionHandler::class              => Handler\PhpCollectionHandler::class,
            DoctrineProxySubscriber::class                   => DoctrineProxySubscriber::class,

            DataContainerBuilder\XmlBuilderInterface::class => DataContainerBuilder\XmlBuilder::class

        ],
        'factories'          => [
            MetadataDriver\LazyLoadingDriver::class => MetadataDriver\LazyLoadingDriverFactory::class,
            DriverChain::class                      => MetadataDriver\DriverChainFactory::class,
            'defaultAnnotationReader'               => MetadataReader\DefaultAnnotationReaderFactory::class,

            JmsSerializerMetadataDriver\AnnotationDriver::class   => MetadataDriver\AnnotationDriverFactory::class,
            JmsSerializerMetadataDriver\DoctrineTypeDriver::class => MetadataDriver\DoctrineTypeDriverFactory::class,

            FileLocator::class                                 => MetadataDriver\FileLocatorFactory::class,
            JmsHandlerRegistry::class                          => HandlerRegistry\HandlerRegistryFactory::class,
            ManagerRegistry::class                             => Util\ManagerRegistryFactory::class,
            ObjectConstructor\DoctrineObjectConstructor::class => ObjectConstructor\DoctrineObjectConstructorFactory::class,
            Serializer\JsonSerializationVisitor::class         => Visitor\JsonSerializationVisitorFactory::class,
            Serializer\XmlSerializationVisitor::class          => Visitor\XmlSerializationVisitorFactory::class,
            Serializer\YamlSerializationVisitor::class         => Visitor\YamlSerializationVisitorFactory::class,
            Serializer\JsonDeserializationVisitor::class       => Visitor\JsonDeserializationVisitorFactory::class,
            Serializer\XmlDeserializationVisitor::class        => Visitor\XmlDeserializationVisitorFactory::class,

            JmsNamingStrategy\CacheNamingStrategy::class              => NamingStrategy\CacheNamingStrategyFactory::class,
            JmsNamingStrategy\SerializedNameAnnotationStrategy::class => NamingStrategy\SerializedNameAnnotationStrategyFactory::class,
            JmsNamingStrategy\IdenticalPropertyNamingStrategy::class  => NamingStrategy\IdenticalPropertyNamingStrategyFactory::class,
            JmsNamingStrategy\CamelCaseNamingStrategy::class          => NamingStrategy\CamelCaseNamingStrategyFactory::class,

            JmsEventDispatcher::class => EventDispatcher\EventDispatcherFactory::class,

            EventDispatcher\XmlDoctrineObjectConstructorSubscriber::class => EventDispatcher\XmlDoctrineObjectConstructorSubscriberFactory::class,

            DoctrineObjectEngine\DoctrineObjectEngineInterface::class => DoctrineObjectEngine\DoctrineObjectEngineFactory::class,
            DoctrineObjectEngine\MetadataBuilderInterface::class      => DoctrineObjectEngine\MetadataBuilderFactory::class,
            DoctrineObjectEngine\ImportEngineInterface::class         => DoctrineObjectEngine\ImportEngineFactory::class,

            DoctrineObjectConstructorData::class => DoctrineObjectConstructorDataFactory::class

        ],
        'abstract_factories' => [
            MetadataFactory\MetadataAbstractFactory::class            => MetadataFactory\MetadataAbstractFactory::class,
            MetadataDriver\MetadataDriverAbstractFactory::class       => MetadataDriver\MetadataDriverAbstractFactory::class,
            MetadataDriver\FileDriverAbstractFactory::class           => MetadataDriver\FileDriverAbstractFactory::class,
            MetadataDriver\FileLocatorAbstractFactory::class          => MetadataDriver\FileLocatorAbstractFactory::class,
            HandlerRegistry\HandlerRegistryAbstractFactory::class     => HandlerRegistry\HandlerRegistryAbstractFactory::class,
            ObjectConstructor\ObjectConstructorAbstractFactory::class => ObjectConstructor\ObjectConstructorAbstractFactory::class,
            Visitor\VisitorsMapAbstractFactory::class                 => Visitor\VisitorsMapAbstractFactory::class,
            Visitor\VisitorsAbstractFactory::class                    => Visitor\VisitorsAbstractFactory::class,
            NamingStrategy\NamingStrategyAbstractFactory::class       => NamingStrategy\NamingStrategyAbstractFactory::class,
            EventDispatcher\EventDispatcherAbstractFactory::class     => EventDispatcher\EventDispatcherAbstractFactory::class
        ],
        'shared'             => [
            DoctrineObjectConstructorData::class => false
        ]
    ]
];

