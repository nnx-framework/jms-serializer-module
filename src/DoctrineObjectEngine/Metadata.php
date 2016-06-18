<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Nnx\JmsSerializerModule\DataContainer;

/**
 * Class Metadata
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
class Metadata implements MetadataInterface
{
    /**
     * Ассоциция ведет только на одну сущность
     *
     * @var string
     */
    const SINGLE_VALUE_ASSOCIATION = 'singleValuedAssociation';

    /**
     * Ассоциция ведет только на одну сущность
     *
     * @var string
     */
    const COLLECTION_VALUE_ASSOCIATION = 'collectionValuedAssociation';

    /**
     * Ключем является id контейнера с данными для фикстуры, а значением является имя класса сущности
     *
     * @var array
     */
    protected $dataContainerIdToEntityClassName = [];

    /**
     * id контейнера с данными, для которого стоит искать уже созданную сущность в базе данных
     *
     * @var array
     */
    protected $candidatesForSearchInDb = [];

    /**
     * Ключем является id контейнера с данными, а значением массив со следующей структурой:
     * имя ассоциации => массив с id контейнеров, на которые ведет ассоциация
     *
     * @var array
     */
    protected $associationMap = [];

    /**
     * Добавляет элемент в карту ассоциаций
     *
     * @param DataContainer\EntityInterface $fromDataItem
     * @param DataContainer\EntityInterface $toDataItem
     * @param        $association
     *
     * @return $this
     */
    public function addAssociationInfo(DataContainer\EntityInterface $fromDataItem, DataContainer\EntityInterface $toDataItem, DataContainer\Association $association)
    {
        $fromDataItemId = $fromDataItem->getId();
        if (!array_key_exists($fromDataItemId, $this->associationMap)) {
            $this->associationMap[$fromDataItemId] = [];
        }
        $associationName = $association->getName();
        if (!array_key_exists($associationName, $this->associationMap[$fromDataItemId])) {
            $this->associationMap[$fromDataItemId][$associationName] = [];
        }
        $toDataItemId = $toDataItem->getId();
        $this->associationMap[$fromDataItemId][$associationName][$toDataItemId] = $toDataItemId;

        return $this;
    }

    /**
     * Добавляет обратную связь
     *
     * @param DataContainer\EntityInterface $fromDataItem
     * @param DataContainer\EntityInterface $toDataItem
     * @param        $associationName
     *
     * @return $this
     */
    public function addReverseAssociationInfo(DataContainer\EntityInterface $fromDataItem, DataContainer\EntityInterface $toDataItem, $associationName)
    {
        $fromDataItemId = $fromDataItem->getId();
        if (!array_key_exists($fromDataItemId, $this->associationMap)) {
            $this->associationMap[$fromDataItemId] = [];
        }
        if (!array_key_exists($associationName, $this->associationMap[$fromDataItemId])) {
            $this->associationMap[$fromDataItemId][$associationName] = [];
        }
        $toDataItemId = $toDataItem->getId();
        $this->associationMap[$fromDataItemId][$associationName][$toDataItemId] = $toDataItemId;

        return $this;
    }

    /**
     * Связывает id контейнера с данными, с именем класса сущности
     *
     * @param DataContainer\EntityInterface $dataItem
     * @param string $entityClassName
     *
     * @return $this
     */
    public function linkDataContainerToEntityClassName(DataContainer\EntityInterface $dataItem, $entityClassName)
    {
        $this->dataContainerIdToEntityClassName[$dataItem->getId()] = $entityClassName;

        return $this;
    }



    /**
     * Возвращает имя класса сущности, который привязан к контейнеру
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return string
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     */
    public function getEntityClassNameByDataContainer(DataContainer\EntityInterface $dataItem)
    {
        $dataItemId = $dataItem->getId();
        if (!array_key_exists($dataItemId, $this->dataContainerIdToEntityClassName)) {
            $errMsg = sprintf('Entity class name not found for data container:#id %s', $dataItemId);
            throw new Exception\RuntimeException($errMsg);
        }

        return $this->dataContainerIdToEntityClassName[$dataItemId];
    }


    /**
     * Возвращает данные о асоцицих, на основе контейнера
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return array
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     */
    public function getAssociationsForEntity(DataContainer\EntityInterface $dataItem)
    {
        $dataItemId = $dataItem->getId();
        if (!array_key_exists($dataItemId, $this->associationMap)) {
            $errMsg = sprintf('Associations for data container id: %s not found', $dataItemId);
            throw new Exception\RuntimeException($errMsg);
        }

        return $this->associationMap[$dataItemId];
    }

    /**
     * Проверяет есть ли для контейнера с данными связанная ассоциация
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return boolean
     */
    public function hasAssociationsForEntity(DataContainer\EntityInterface $dataItem)
    {
        return array_key_exists($dataItem->getId(), $this->associationMap);
    }
}
