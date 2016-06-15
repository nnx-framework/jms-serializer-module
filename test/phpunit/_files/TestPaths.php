<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\PhpUnit\TestData;

/**
 * Class TestPaths
 *
 * @package Nnx\JmsSerializerModule\PhpUnit\TestData
 */
class TestPaths
{

    /**
     * Путь до директории модуля
     *
     * @return string
     */
    public static function getPathToModule()
    {
        return __DIR__ . '/../../../';
    }

    /**
     * Путь до конфига приложения по умолчанию
     *
     * @return string
     */
    public static function getPathToDefaultAppConfig()
    {
        return  __DIR__ . '/../_files/DefaultApp/application.config.php';
    }

    /**
     * Возвращает путь путь до директории в которой создаются прокси классы для сущностей доктрины
     *
     * @return string
     */
    public static function getPathToDoctrineProxyDir()
    {
        return __DIR__ . '/../../../data/test/Proxies/';
    }
}
