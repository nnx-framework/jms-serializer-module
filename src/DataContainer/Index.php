<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainer;

/**
 * Class Index
 *
 * @package Nnx\JmsSerializerModule\DataContainer
 */
class Index
{
    /**
     * Все контейнеры с даннными для фикстуры
     *
     * @var EntityInterface[]
     */
    protected $entities = [];

    /**
     * Ключем является уровень вложеннесоти контейнера с данными, а значением массив контейнеров, расположенных на данном
     * уровен вложенности
     *
     * @var array
     */
    protected $levelToEntities = [];

    /**
     * Строит индекс для сущностей
     *
     * @param EntityInterface $entity
     *
     * @return $this
     */
    public function indexEntity(EntityInterface $entity)
    {
        $level = $entity->getLevel();
        if (!array_key_exists($level, $this->levelToEntities)) {
            $this->levelToEntities[$level] = [];
        }
        $this->levelToEntities[$level][] = $entity;

        $this->entities[$entity->getId()] = $entity;
        return $this;
    }

    /**
     * Возвращает список всех контейнеров с данными
     *
     * @return EntityInterface[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Возвращает контейнер с данными, на основе его id
     *
     * @param $id
     *
     * @return mixed
     * @throws \Nnx\JmsSerializerModule\DataContainer\Exception\RuntimeException
     */
    public function getEntityById($id)
    {
        if (!array_key_exists($id, $this->entities)) {
            $errMsg = sprintf('Data container id: %s not found', $id);
            throw new Exception\RuntimeException($errMsg);
        }

        return $this->entities[$id];
    }
}
