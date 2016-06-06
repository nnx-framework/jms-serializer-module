<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */

use Nnx\JmsSerializerModule\PhpUnit\TestData\TestPaths;
use Nnx\JmsSerializerModule\Module;
use Nnx\ModuleOptions\Module as ModuleOptions;

return [
    'modules'                 => [
        ModuleOptions::MODULE_NAME,
        Module::MODULE_NAME
    ],
    'module_listener_options' => [
        'module_paths'      => [
            Module::MODULE_NAME => TestPaths::getPathToModule(),
        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];
