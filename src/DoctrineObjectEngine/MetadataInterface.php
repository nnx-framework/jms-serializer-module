<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Nnx\JmsSerializerModule\DataContainer;

/**
 * Interface MetadataInterface
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
interface MetadataInterface
{

    /**
     * Связывает id контейнера с данными, с именем класса сущности
     *
     * @param DataContainer\EntityInterface $dataItem
     * @param string $entityClassName
     *
     * @return $this
     */
    public function linkDataContainerToEntityClassName(DataContainer\EntityInterface $dataItem, $entityClassName);

    /**
     * Добавляет элемент в карту ассоциаций
     *
     * @param DataContainer\EntityInterface $fromDataItem
     * @param DataContainer\EntityInterface $toDataItem
     * @param        $association
     *
     * @return $this
     */
    public function addAssociationInfo(DataContainer\EntityInterface $fromDataItem, DataContainer\EntityInterface $toDataItem, DataContainer\Association $association);


    /**
     * Добавляет обратную связь
     *
     * @param DataContainer\EntityInterface $fromDataItem
     * @param DataContainer\EntityInterface $toDataItem
     * @param        $associationName
     *
     * @return $this
     */
    public function addReverseAssociationInfo(DataContainer\EntityInterface $fromDataItem, DataContainer\EntityInterface $toDataItem, $associationName);


    /**
     * Возвращает имя класса сущности, который привязан к контейнеру
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return string
     */
    public function getEntityClassNameByDataContainer(DataContainer\EntityInterface $dataItem);

    /**
     * Возвращает данные о асоцицих, на основе контейнера
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return array
     */
    public function getAssociationsForEntity(DataContainer\EntityInterface $dataItem);

    /**
     * Проверяет есть ли для контейнера с данными связанная ассоциация
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return boolean
     */
    public function hasAssociationsForEntity(DataContainer\EntityInterface $dataItem);
}
