<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule;

use Nnx\JmsSerializerModule\Serializer;
return [
    'serializers' => [
        'factories'          => [
            Serializer\JmsSerializer::class => Serializer\JmsSerializerFactory::class
        ],
        'abstract_factories' => [
            Serializer\JmsSerializerAbstractFactory::class => Serializer\JmsSerializerAbstractFactory::class
        ]
    ],
];


