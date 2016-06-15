<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */

use Nnx\JmsSerializerModule\PhpUnit\TestData\TestPaths;
use Nnx\JmsSerializerModule\Module;
use Nnx\ModuleOptions\Module as ModuleOptions;
use Nnx\Doctrine\Module as DoctrineModule;
use Nnx\ZF2TestToolkit\Listener\InitTestAppListener;
use Nnx\ZF2TestToolkit\Listener\StopDoctrineLoadCliPostEventListener;

return [
    'modules'                 => [
        'DoctrineModule',
        ModuleOptions::MODULE_NAME,
        DoctrineModule::MODULE_NAME,
        Module::MODULE_NAME
    ],
    'module_listener_options' => [
        'module_paths'      => [
            Module::MODULE_NAME => TestPaths::getPathToModule(),
        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ],
    'service_manager'         => [
        'invokables' => [
            InitTestAppListener::class => InitTestAppListener::class,
            StopDoctrineLoadCliPostEventListener::class => StopDoctrineLoadCliPostEventListener::class
        ]
    ],
    'listeners'               => [
        InitTestAppListener::class,
        StopDoctrineLoadCliPostEventListener::class
    ]
];
