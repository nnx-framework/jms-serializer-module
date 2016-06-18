<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Doctrine\Common\Persistence\ObjectManager;
use Nnx\JmsSerializerModule\DataContainer;

/**
 * Class MetadataBuilder
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
class MetadataBuilder implements MetadataBuilderInterface
{
    /**
     * Контейнер с данными для фикстуры
     *
     * @var DataContainer\DataContainerInterface
     */
    private $dataContainer;

    /**
     * Менеджер доктрины
     *
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var MetadataInterface
     */
    private $metadata;

    /**
     * @var MetadataInterface
     */
    protected $metadataPrototype;

    /**
     * MetadataBuilder constructor.
     *
     * @param MetadataInterface $metadataPrototype
     */
    public function __construct(MetadataInterface $metadataPrototype)
    {
        $this->metadataPrototype = $metadataPrototype;
    }

    /**
     * Подготавливает метаданные
     *
     * @param DataContainer\DataContainerInterface $dataContainer
     * @param                        $entityClassName
     * @param ObjectManager          $objectManager
     *
     * @return MetadataInterface
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     */
    public function buildMetadata(DataContainer\DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager)
    {
        $this->dataContainer = $dataContainer;
        $this->objectManager = $objectManager;
        $this->metadata = clone $this->metadataPrototype;

        $entities = $dataContainer->getEntities();

        foreach ($entities as $dataItem) {
            $this->buildMetadataForDataContainer($dataItem, $entityClassName);
        }


        return $this->metadata;
    }

    /**
     * Подготавливает метаданные для сущности
     *
     * @param DataContainer\EntityInterface $dataItem
     * @param        $entityClassName
     *
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     */
    protected function buildMetadataForDataContainer(DataContainer\EntityInterface $dataItem, $entityClassName)
    {
        $this->metadata->linkDataContainerToEntityClassName($dataItem, $entityClassName);

        $dataItemAssociations = $dataItem->getAssociations();

        $entityMetadata = $this->objectManager->getClassMetadata($entityClassName);

        foreach ($dataItemAssociations as $dataItemAssociation) {
            $associationDataItems = $dataItemAssociation->getEntities();
            $associationName = $dataItemAssociation->getName();

            $associationTargetClass = $entityMetadata->getAssociationTargetClass($associationName);

            foreach ($associationDataItems as $associationDataItem) {
                $this->buildReverseAssociationMetadata($associationDataItem, $associationTargetClass, $dataItemAssociation);
                $this->metadata->addAssociationInfo($dataItem, $associationDataItem, $dataItemAssociation);
                $this->buildMetadataForDataContainer($associationDataItem, $associationTargetClass);
            }
        }
    }

    /**
     * Добавляет метаданные для двухсторонних ассоциаций
     *
     * @param DataContainer\EntityInterface      $childEntity
     * @param             $childEntityClassName
     * @param DataContainer\Association $targetAssociation
     */
    protected function buildReverseAssociationMetadata(DataContainer\EntityInterface $childEntity, $childEntityClassName, DataContainer\Association $targetAssociation)
    {
        $childEntityMetadata = $this->objectManager->getClassMetadata($childEntityClassName);
        $childEntityAssociationNames = $childEntityMetadata->getAssociationNames();

        $targetAssociationName = $targetAssociation->getName();
        foreach ($childEntityAssociationNames as $childEntityAssociationName) {
            if ($childEntityMetadata->isAssociationInverseSide($childEntityAssociationName)) {
                $associationMappedByTargetField = $childEntityMetadata->getAssociationMappedByTargetField($childEntityAssociationName);

                if ($associationMappedByTargetField === $targetAssociationName) {
                    $this->metadata->addReverseAssociationInfo($childEntity, $childEntity->getParentEntity(), $childEntityAssociationName);
                }
            }
        }
    }


    /**
     * Проверка контейнера с данными
     *
     * @param DataContainer\EntityInterface $dataItem
     * @param        $entityClassName
     *
     * @return void
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     */
    protected function validateDataItem(DataContainer\EntityInterface $dataItem, $entityClassName)
    {
        $dataItemAssociations = $dataItem->getAssociations();
        $properties = $dataItem->getProperties();

        $entityMetadata = $this->objectManager->getClassMetadata($entityClassName);
        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if (!$entityMetadata->hasField($propertyName)) {
                $errMsg = sprintf('Property %s not found in %s', $propertyName, $entityClassName);
                throw new Exception\RuntimeException($errMsg);
            }
        }

        foreach ($dataItemAssociations as $dataItemAssociation) {
            $dataItemAssociationName = $dataItemAssociation->getName();

            if (!$entityMetadata->hasAssociation($dataItemAssociationName)) {
                $errMsg = sprintf('Association %s not found in %s', $dataItemAssociationName, $entityClassName);
                throw new Exception\RuntimeException($errMsg);
            }
        }

        if (0 === count($dataItemAssociations) && 0 === count($properties)) {
            $errMsg = sprintf('Data container id:%s is empty', $dataItem->getId());
            throw new Exception\RuntimeException($errMsg);
        }
    }
}
