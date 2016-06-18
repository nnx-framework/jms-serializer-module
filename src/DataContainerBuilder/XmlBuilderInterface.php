<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainerBuilder;

use Nnx\JmsSerializerModule\DataContainer\DataContainerInterface;
use Nnx\JmsSerializerModule\DataContainer\EntityInterface;
use SimpleXMLElement;

/**
 * Interface XmlBuilderInterface
 *
 * @package Nnx\JmsSerializerModule\DataContainerBuilder
 */
interface XmlBuilderInterface
{
    /**
     * Подготавливает нормализованный контейнер с данными на основе узла SimpleXMLElement
     *
     * @param $resource
     *
     * @return DataContainerInterface
     */
    public function loadDataFromResource(SimpleXMLElement $resource);

    /**
     * Определяет есть ли готовые данные для данного элемента
     *
     * @param SimpleXMLElement $resource
     *
     * @return boolean
     */
    public function hasDataInCache(SimpleXMLElement $resource);

    /**
     * Возвращает контейнер с данными из кеша
     *
     * @param SimpleXMLElement $resource
     *
     * @return DataContainerInterface
     */
    public function getDataContainerFromCache(SimpleXMLElement $resource);

    /**
     * Возвращает контейнер с данными для сущности
     *
     * @param SimpleXMLElement $resource
     *
     * @return EntityInterface
     */
    public function getEntityFromCache(SimpleXMLElement $resource);
}
