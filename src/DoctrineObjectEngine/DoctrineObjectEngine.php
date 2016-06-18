<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Doctrine\Common\Persistence\ObjectManager;
use Nnx\JmsSerializerModule\DataContainer;

/**
 * Class DoctrineObjectEngine
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
class DoctrineObjectEngine implements DoctrineObjectEngineInterface
{

    /**
     * Компонент отвечающий за сборку метаданных
     *
     * @var MetadataBuilderInterface
     */
    protected $metadataBuilder;

    /**
     * Движок отвечающий за заполнение базы данных
     *
     * @var ImportEngineInterface
     */
    protected $importEngine;

    /**
     * DoctrineObjectEngine constructor.
     *
     * @param MetadataBuilderInterface $metadataBuilder
     * @param ImportEngineInterface    $importEngine
     */
    public function __construct(
        MetadataBuilderInterface $metadataBuilder,
        ImportEngineInterface $importEngine
    ) {
        $this->setMetadataBuilder($metadataBuilder);
        $this->setImportEngine($importEngine);
    }

    /**
     * Возвращает компонент отвечающий за сборку метаданных
     *
     * @return MetadataBuilderInterface
     */
    public function getMetadataBuilder()
    {
        return $this->metadataBuilder;
    }

    /**
     * Возвращает движок, отвечающий за заполнение базы данных
     *
     * @return ImportEngineInterface
     */
    public function getImportEngine()
    {
        return $this->importEngine;
    }

    /**
     * Устанавливает движок отвечающий за заполнение базы данных
     *
     * @param ImportEngineInterface $importEngine
     *
     * @return $this
     */
    public function setImportEngine(ImportEngineInterface $importEngine)
    {
        $this->importEngine = $importEngine;

        return $this;
    }


    /**
     * Устанавливает компонент отвечающий за сборку метаданных
     *
     * @param MetadataBuilderInterface $metadataBuilder
     *
     * @return $this
     */
    public function setMetadataBuilder(MetadataBuilderInterface $metadataBuilder)
    {
        $this->metadataBuilder = $metadataBuilder;

        return $this;
    }


    /**
     * Загрузка данных
     *
     * @param DataContainer\DataContainerInterface $dataContainer
     * @param string $entityClassName
     * @param ObjectManager $objectManager
     *
     * @return $this
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\InvalidContextException
     */
    public function import(DataContainer\DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager)
    {
        $metadata = $this->getMetadataBuilder()->buildMetadata($dataContainer, $entityClassName, $objectManager);

        $this->getImportEngine()->run($dataContainer, $metadata, $objectManager);
        $objectManager->flush();
        return $this;
    }

    /**
     * Получает сущность, которая соответствует контейнеру с данными
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return mixed
     *
     */
    public function getDoctrineEntityByDataContainer(DataContainer\EntityInterface $dataItem)
    {
        return $this->getImportEngine()->getDoctrineEntityByDataContainer($dataItem);
    }

    /**
     * Удаление данных
     *
     * @param DataContainer\DataContainerInterface $dataContainer
     * @param string $entityClassName
     * @param ObjectManager $objectManager
     *
     * @return $this
     */
    public function purge(DataContainer\DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager)
    {
    }
}
