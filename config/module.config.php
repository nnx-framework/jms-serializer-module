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

return [
    Module::CONFIG_KEY => [
        'serializers' => [
            'default' => [
                'metadataFactory' => 'nnxJmsSerializer.metadataFactory.default',
                'handlerRegistry' => 'nnxJmsSerializer.handlerRegistry.default',
                'objectConstructor' => 'nnxJmsSerializer.objectConstructor.default',
                'serializationVisitors' => 'nnxJmsSerializer.serializationVisitors.default',
                'deserializationVisitors' => 'nnxJmsSerializer.deserializationVisitors.default',
                'eventDispatcher'  => 'nnxJmsSerializer.eventDispatcher.default',
                'typeParser' => 'nnxJmsSerializer.typeParser.default'
            ],
        ],
        'metadataFactories' => [
            'default' => [
                'name' => MetadataFactory::class,
                'options' => [
                    'metadataDriver' => 'nnxJmsSerializer.metadataDriver.default',
                    'hierarchyMetadataClass' => ClassHierarchyMetadata::class,
                    'debug' => false
                ]
            ]
        ],
        'metadataDrivers' => [
            'default' => [
                'name' => DriverChain::class,
                'options' => [

                ]
            ]
        ],
        'handlerRegistries' => [
            'default' => [
                'name' => HandlerRegistry::class,
                'options' => [
                    'handlers' => [

                    ]
                ]

            ]
        ],
        'objectConstructors' => [
            'default' => [
                'name' => DoctrineObjectConstructor::class,
                'options' => [

                ]

            ]
        ],
        'serializationVisitors' => [
            'default' => [

            ]
        ],
        'deserializationVisitors' => [
            'default' => [

            ]
        ],
        'eventDispatcher' => [
            'default' => [
                'name' => EventDispatcher::class,
                'options' => [
                    'subscribers' => [

                    ]
                ]
            ]
        ],
        'typeParser' => [
            'default' => [
                'name' => EventDispatcher::class,
                'options' => [
                    'subscribers' => [

                    ]
                ]
            ]
        ]

    ]
];

