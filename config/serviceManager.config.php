<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule;

use Nnx\JmsSerializerModule\ModuleServiceManager\JmsSerializerModuleServiceManager;
use Nnx\JmsSerializerModule\ModuleServiceManager\JmsSerializerModuleServiceManagerFactory;

return [
    'service_manager' => [
        'invokables'         => [
            
        ],
        'factories'          => [
            JmsSerializerModuleServiceManager::class => JmsSerializerModuleServiceManagerFactory::class
        ],
        'abstract_factories' => [

        ]
    ],
];


