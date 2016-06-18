<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Doctrine\Common\Persistence\ObjectManager;
use Nnx\JmsSerializerModule\DataContainer;

/**
 * Interface ImportEngineInterface
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
interface ImportEngineInterface
{
    /**
     * Запуск процесса импорта данных
     *
     * @param DataContainer\DataContainerInterface $dataContainer
     * @param MetadataInterface                    $metadata
     * @param ObjectManager                        $objectManager
     */
    public function run(DataContainer\DataContainerInterface $dataContainer, MetadataInterface $metadata, ObjectManager $objectManager);

    /**
     * Получает сущность, которая соответствует контейнеру с данными
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return mixed
     */
    public function getDoctrineEntityByDataContainer(DataContainer\EntityInterface $dataItem);
}
