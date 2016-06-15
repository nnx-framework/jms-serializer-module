<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule;

use JMS\Serializer\Construction\DoctrineObjectConstructor;
use Metadata\MetadataFactory;
use Metadata\Driver\DriverChain;
use Metadata\ClassHierarchyMetadata;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use Nnx\JmsSerializerModule\Serializer\JmsSerializer;
use Nnx\JmsSerializerModule\MetadataDriver\LazyLoadingDriver;
use JMS\Serializer\Metadata\Driver as JmsSerializerMetadataDriver;
use Nnx\JmsSerializerModule\MetadataReader;
use Metadata\Driver\FileLocator;
use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\Construction\UnserializeObjectConstructor;
use JMS\Serializer;
use JMS\Serializer\Naming;

return [
    Module::CONFIG_KEY => [
        'serializers'             => [
            'default' => [
                'name'    => JmsSerializer::class,
                'options' => [
                    'metadataFactory'         => 'nnxJmsSerializer.metadataFactory.default',
                    'handlerRegistry'         => 'nnxJmsSerializer.handlerRegistry.default',
                    'objectConstructor'       => 'nnxJmsSerializer.objectConstructor.doctrineObjectConstructor',
                    'serializationVisitors'   => 'nnxJmsSerializer.serializationVisitors.default',
                    'deserializationVisitors' => 'nnxJmsSerializer.deserializationVisitors.default',
                    'eventDispatcher'         => 'nnxJmsSerializer.eventDispatchers.default',
                    'typeParser'              => 'nnxJmsSerializer.typeParser.default'
                ]
            ],
        ],
        'metadataFactories'       => [
            'default' => [
                'name'    => MetadataFactory::class,
                'options' => [
                    'metadataDriver'         => 'nnxJmsSerializer.metadataDriver.default',
                    'hierarchyMetadataClass' => ClassHierarchyMetadata::class,
                    'debug'                  => true
                ]
            ]
        ],
        'metadataDrivers'         => [
            'defaultDriversChain'     => [
                'name'    => DriverChain::class,
                'options' => [
                    'drivers' => [
                        'annotationDriver' => 'nnxJmsSerializer.metadataDriver.defaultAnnotationDriver',
                        'phpDriver'        => 'nnxJmsSerializer.metadataDriver.defaultPhpDriver',
                        'xmlDriver'        => 'nnxJmsSerializer.metadataDriver.defaultXmlDriver',
                        'yamlDriver'       => 'nnxJmsSerializer.metadataDriver.defaultYamlDriver'
                    ]
                ]
            ],
            'default'                 => [
                'name'    => LazyLoadingDriver::class,
                'options' => [
                    'realDriverId' => 'nnxJmsSerializer.metadataDriver.defaultDriversChain'
                ]
            ],
            'defaultAnnotationDriver' => [
                'name'    => JmsSerializerMetadataDriver\AnnotationDriver::class,
                'options' => [
                    /** @see \Nnx\JmsSerializerModule\MetadataReader\DefaultAnnotationReaderFactory */
                    'reader' => 'defaultAnnotationReader'
                ]
            ],
            'defaultPhpDriver'        => [
                'name'    => JmsSerializerMetadataDriver\PhpDriver::class,
                'options' => [
                    'fileLocator' => 'nnxJmsSerializer.fileLocators.defaultPhpDriver'
                ]
            ],
            'defaultYamlDriver'       => [
                'name'    => JmsSerializerMetadataDriver\YamlDriver::class,
                'options' => [
                    'fileLocator' => 'nnxJmsSerializer.fileLocators.defaultYamlDriver'
                ]
            ],
            'defaultXmlDriver'        => [
                'name'    => JmsSerializerMetadataDriver\XmlDriver::class,
                'options' => [
                    'fileLocator' => 'nnxJmsSerializer.fileLocators.defaultXmlDriver'
                ]
            ]
        ],
        'fileLocators'            => [
            'defaultPhpDriver'  => [
                'name'    => FileLocator::class,
                'options' => [
                    'directories' => []
                ]
            ],
            'defaultYamlDriver' => [
                'name'    => FileLocator::class,
                'options' => [
                    'directories' => []
                ]
            ],
            'defaultXmlDriver'  => [
                'name'    => FileLocator::class,
                'options' => [
                    'directories' => []
                ]
            ]
        ],
        'handlerRegistries'       => [
            'default' => [
                'name'    => HandlerRegistry::class,
                'options' => [
                    'handlers' => [

                    ]
                ]

            ]
        ],
        'objectConstructors'      => [
            'doctrineObjectConstructor'    => [
                'name'    => DoctrineObjectConstructor::class,
                'options' => [
                    'managerRegistry'     => ManagerRegistry::class,
                    'fallbackConstructor' => 'nnxJmsSerializer.objectConstructor.unserializeObjectConstructor'
                ]
            ],
            'unserializeObjectConstructor' => [
                'name'    => UnserializeObjectConstructor::class,
                'options' => [

                ]
            ]
        ],
        'namingStrategies' => [
            'cachedSerializedNameAnnotation' => [
                'name' => Naming\CacheNamingStrategy::class,
                'options' => [
                    'delegate' => 'nnxJmsSerializer.namingStrategies.serializedNameAnnotation'
                ]
            ],
            'serializedNameAnnotation' => [
                'name' => Naming\SerializedNameAnnotationStrategy::class,
                'options' => [
                    'delegate' => 'nnxJmsSerializer.namingStrategies.camelCaseNaming'
                ]
            ],
            'identicalNaming' => [
                'name' => Naming\IdenticalPropertyNamingStrategy::class,
                'options' => []
            ],
            'camelCaseNaming' => [
                'name' => Naming\CamelCaseNamingStrategy::class,
                'options' => [
                    'separator' => '_',
                    'lowerCase' => true
                ]
            ]
        ],
        'visitors' => [
            'jsonSerializationVisitor' => [
                'name' => Serializer\JsonSerializationVisitor::class,
                'options' => [
                    'namingStrategy' => 'nnxJmsSerializer.namingStrategies.cachedSerializedNameAnnotation',
                    'jsonOptions' => [
                        'options' => 0
                    ]
                ]
            ],
            'xmlSerializationVisitor' => [
                'name' => Serializer\XmlSerializationVisitor::class,
                'options' => [
                    'namingStrategy' => 'nnxJmsSerializer.namingStrategies.cachedSerializedNameAnnotation',
                ]

            ],
            'yamlSerializationVisitor' => [
                'name' => Serializer\YamlSerializationVisitor::class,
                'options' => [
                    'namingStrategy' => 'nnxJmsSerializer.namingStrategies.cachedSerializedNameAnnotation',
                ]
            ],
            'jsonDeserializationVisitor' => [
                'name' => Serializer\JsonDeserializationVisitor::class,
                'options' => [
                    'namingStrategy' => 'nnxJmsSerializer.namingStrategies.cachedSerializedNameAnnotation',
                ]
            ],
            'xmlDeserializationVisitor' => [
                'name' => Serializer\XmlDeserializationVisitor::class,
                'options' => [
                    'namingStrategy' => 'nnxJmsSerializer.namingStrategies.cachedSerializedNameAnnotation',
                    'doctypeWhitelist' => [

                    ]
                ]
            ]
        ],
        'serializationVisitors'   => [
            'default' => [
                'json' => 'nnxJmsSerializer.visitors.jsonSerializationVisitor',
                'xml'  => 'nnxJmsSerializer.visitors.xmlSerializationVisitor',
                'yml'  => 'nnxJmsSerializer.visitors.yamlSerializationVisitor',
            ]
        ],
        'deserializationVisitors' => [
            'default' => [
                'json' => 'nnxJmsSerializer.visitors.jsonDeserializationVisitor',
                'xml'  => 'nnxJmsSerializer.visitors.xmlDeserializationVisitor',
            ]
        ],
        'eventDispatchers'        => [
            'default' => [
                'name'    => EventDispatcher::class,
                'options' => [
                    'subscribers' => [

                    ]
                ]
            ]
        ],
        'annotationCache'         => 'doctrine.cache.array'
    ]
];

