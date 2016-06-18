<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainer;

/**
 * Interface EntityInterface
 *
 * @package Nnx\JmsSerializerModule\DataContainer
 */
interface EntityInterface
{
    /**
     * Возвращает уровень, на котором расположена сущность
     *
     * @return int
     */
    public function getLevel();

    /**
     * Устанавливает уровень на котором расположена сущность
     *
     * @param int $level
     *
     * @return $this
     */
    public function setLevel($level);

    /**
     * Возвращает родительскую сущность
     *
     * @return EntityInterface|null
     */
    public function getParentEntity();

    /**
     * Устанавливает родительскую сущность
     *
     * @param EntityInterface $parentEntity
     *
     * @return $this
     */
    public function setParentEntity(EntityInterface $parentEntity = null);

    /**
     * Добавляет связь на вложенную сущность
     *
     * @param Association $association
     *
     * @return $this
     */
    public function addAssociation(Association $association);

    /**
     * Возвращает связи
     *
     * @return Association[]
     */
    public function getAssociations();

    /**
     * Добавляет поле
     *
     * @param Property $property
     *
     * @return $this
     */
    public function addProperty(Property $property);

    /**
     * Возвращает набор полей сущности
     *
     * @return Property[]
     */
    public function getProperties();

    /**
     * Определяет, есть ли связь, с заданным именем
     *
     * @param $name
     *
     * @return bool
     */
    public function hasAssociation($name);


    /**
     * Определяет, есть ли связь, с заданным именем
     *
     * @param $name
     *
     * @return Association
     * @throws \Nnx\JmsSerializerModule\DataContainer\Exception\InvalidArgumentException
     */
    public function getAssociation($name);

    /**
     * Возвращает внутренний идендификатор контейнера с данынми для фикстуры
     *
     * @return int
     */
    public function getId();
}
