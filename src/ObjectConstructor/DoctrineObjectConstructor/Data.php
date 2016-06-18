<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\ObjectConstructor\DoctrineObjectConstructor;

use Nnx\JmsSerializerModule\DataContainer\DataContainerInterface;
use Nnx\JmsSerializerModule\DataContainer\EntityInterface;

/**
 * Class Data
 *
 * @package Nnx\JmsSerializerModule\ObjectConstructor\DoctrineObjectConstructor
 */
class Data implements DataInterface
{
    /**
     * Контейнер с данными
     *
     * @var DataContainerInterface
     */
    protected $dataContainer;

    /**
     * Сущность для которой происходит запрос
     *
     * @var EntityInterface
     */
    protected $entity;

    /**
     * Data constructor.
     *
     * @param DataContainerInterface $dataContainer
     * @param EntityInterface        $entity
     */
    public function __construct(DataContainerInterface $dataContainer, EntityInterface $entity)
    {
        $this->dataContainer = $dataContainer;
        $this->entity = $entity;
    }

    /**
     * Возвращает контейнер с данными
     *
     * @return DataContainerInterface
     */
    public function getDataContainer()
    {
        return $this->dataContainer;
    }

    /**
     * Возвращает сущность для которой происходит запрос
     *
     * @return EntityInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
