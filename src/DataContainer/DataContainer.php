<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainer;

/**
 * Class DataContainer
 *
 * @package Nnx\JmsSerializerModule\DataContainer
 */
class DataContainer implements DataContainerInterface
{
    /**
     * Внутренний идендификатор контейнера с данынми для фикстуры
     *
     * @var integer
     */
    protected $id;

    /**
     * Поле используемое для генерации id
     *
     * @var int
     */
    protected static $idGenerator = 0;

    /**
     * @var EntityInterface[]
     */
    protected $entities = [];

    /**
     * Хранилище индексов
     *
     * @var Index
     */
    protected $index;

    /**
     * DataContainer constructor.
     *
     * @param Index $index
     */
    public function __construct(Index $index)
    {
        $this->index = $index;
    }

    /**
     * Добавляет информацию о данных для сущности
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
     * Возвращает список контейнеров с данными для заполнения бд
     *
     * @return EntityInterface[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Возвращает хранилище индексов
     *
     * @return Index
     */
    public function getIndex()
    {
        return $this->index;
    }


    /**
     * Возвращает внутренний идендификатор контейнера
     *
     * @return int
     */
    public function getId()
    {
        if (null === $this->id) {
            static::$idGenerator += 1;
            $this->id = static::$idGenerator;
        }
        return $this->id;
    }
}
