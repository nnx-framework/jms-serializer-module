<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\ObjectConstructor\DoctrineObjectConstructor;

use Nnx\JmsSerializerModule\DataContainer\DataContainerInterface;
use Nnx\JmsSerializerModule\DataContainer\EntityInterface;

/**
 * Interface DataInterface
 *
 * @package Nnx\JmsSerializerModule\ObjectConstructor\DoctrineObjectConstructor
 */
interface DataInterface
{
    /**
     * Data constructor.
     *
     * @param DataContainerInterface $dataContainer
     * @param EntityInterface        $entity
     */
    public function __construct(DataContainerInterface $dataContainer, EntityInterface $entity);


    /**
     * Возвращает контейнер с данными
     *
     * @return DataContainerInterface
     */
    public function getDataContainer();

    /**
     * Возвращает сущность для которой происходит запрос
     *
     * @return EntityInterface
     */
    public function getEntity();
}
