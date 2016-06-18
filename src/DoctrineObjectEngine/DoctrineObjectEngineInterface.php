<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Doctrine\Common\Persistence\ObjectManager;
use Nnx\JmsSerializerModule\DataContainer;

/**
 * Interface DoctrineObjectEngineInterface
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
interface DoctrineObjectEngineInterface
{
    /**
     * Загрузка данных
     *
     * @param DataContainer\DataContainerInterface $dataContainer
     * @param string $entityClassName
     * @param ObjectManager $objectManager
     *
     * @return $this
     */
    public function import(DataContainer\DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager);

    /**
     * Удаление данных
     *
     * @param DataContainer\DataContainerInterface $dataContainer
     * @param string $entityClassName
     * @param ObjectManager $objectManager
     *
     * @return $this
     */
    public function purge(DataContainer\DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager);

    /**
     * Получает сущность, которая соответствует контейнеру с данными
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return mixed
     *
     */
    public function getDoctrineEntityByDataContainer(DataContainer\EntityInterface $dataItem);
}
