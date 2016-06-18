<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainer;

/**
 * Interface DataContainerInterface
 *
 * @package Nnx\JmsSerializerModule\DataContainer
 */
interface DataContainerInterface
{

    /**
     * Возвращает список контейнеров с данными для заполнения бд
     *
     * @return EntityInterface[]
     */
    public function getEntities();

    /**
     * Добавляет информацию о данных для сущности
     *
     * @param EntityInterface $entity
     *
     * @return $this
     */
    public function addEntity(EntityInterface $entity);


    /**
     * Возвращает хранилище индексов
     *
     * @return Index
     */
    public function getIndex();

    /**
     * Возвращает внутренний идендификатор контейнера
     *
     * @return int
     */
    public function getId();
}
