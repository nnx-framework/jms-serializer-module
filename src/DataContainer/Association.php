<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainer;

/**
 * Class Association
 *
 * @package Nnx\JmsSerializerModule\DataContainer
 */
class Association
{
    /**
     * Имя ассоциаци
     *
     * @var string
     */
    protected $name;

    /**
     * Сущности на которые указывает связь
     *
     * @var EntityInterface[]
     */
    protected $entities;

    /**
     * Хранилище индексов
     *
     * @var Index
     */
    protected $index;

    /**
     * Association constructor.
     *
     * @param Index $index
     */
    public function __construct(Index $index)
    {
        $this->index = $index;
    }

    /**
     * Устанавливает имя ассоциаци
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Возвращает имя ассоциаци
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Добавляет контейнер с данными о вложенной сущности
     *
     * @param EntityInterface $entity
     *
     * @return $this
     */
    public function addEntity(EntityInterface $entity)
    {
        $this->index->indexEntity($entity);
        $this->entities[] = $entity;

        return $this;
    }

    /**
     * Возвращает контейнер с данными о вложенных сущностях
     *
     * @return EntityInterface[]
     */
    public function getEntities()
    {
        return $this->entities;
    }
}
