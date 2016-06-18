<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Doctrine\Common\Persistence\ObjectManager;
use Nnx\JmsSerializerModule\DataContainer;

/**
 * Interface MetadataBuilderInterface
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
interface MetadataBuilderInterface
{
    /**
     * Подготавливает метаданные
     *
     * @param DataContainer\DataContainerInterface $dataContainer
     * @param                        $entityClassName
     * @param ObjectManager          $objectManager
     *
     * @return MetadataInterface
     */
    public function buildMetadata(DataContainer\DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager);
}
