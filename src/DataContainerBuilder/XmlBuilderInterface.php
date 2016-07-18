<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainerBuilder;

use Nnx\JmsSerializerModule\DataContainer\DataContainerInterface;

/**
 * Interface XmlBuilderInterface
 *
 * @package Nnx\JmsSerializerModule\DataContainerBuilder
 */
interface XmlBuilderInterface
{
    /**
     * Подготавливает нормализованный контейнер с данными на основе строки с данными
     *
     * @param $resource
     *
     * @return DataContainerInterface
     */
    public function loadDataFromResource($resource);

}
