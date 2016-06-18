<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Doctrine\Common\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Doctrine\Common\Util\Inflector;
use Doctrine\Common\Collections\Collection;
use DateTime;
use Nnx\JmsSerializerModule\DataContainer;

/**
 * Class ImportEngine
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
class ImportEngine implements ImportEngineInterface
{

    /**
     * Контейнер с данными
     *
     * @var DataContainer\DataContainerInterface
     */
    private $dataContainer;

    /**
     * Метаданные необходимые для заполнения бд
     *
     * @var MetadataInterface
     */
    private $metadata;

    /**
     * Менеджер объектов доктрины
     *
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * Связывает id контейнера с данными, с сущностью
     *
     * @var array
     */
    private $dataContainerIdToDoctrineEntity = [];

    /**
     * Компонет отвечающий за создание сущностей
     *
     * @var ContainerInterface
     */
    protected $entityLocator;

    /**
     * ImportEngine constructor.
     *
     * @param ContainerInterface $entityContainer
     */
    public function __construct(ContainerInterface $entityContainer)
    {
        $this->setEntityLocator($entityContainer);
    }

    /**
     * Запуск процесса импорта данных
     *
     * @param DataContainer\DataContainerInterface $dataContainer
     * @param MetadataInterface                    $metadata
     * @param ObjectManager                        $objectManager
     *
     *
     * @return void
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\InvalidSetValueException
     * @throws \Nnx\JmsSerializerModule\DataContainer\Exception\RuntimeException
     *
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     * @throws \UnexpectedValueException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     */
    public function run(DataContainer\DataContainerInterface $dataContainer, MetadataInterface $metadata, ObjectManager $objectManager)
    {
        $this->dataContainer = $dataContainer;
        $this->metadata = $metadata;
        $this->objectManager = $objectManager;

        $this->initEntities();
        $this->hydrateAssociation();
        $this->hydrateProperties();
    }

    /**
     * Заполняет сущности данными
     *
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\InvalidSetValueException
     */
    protected function hydrateProperties()
    {
        $index = $this->dataContainer->getIndex();
        $dataItems = $index->getEntities();
        foreach ($dataItems as $dataItem) {
            $properties = $dataItem->getProperties();

            $entity = $this->getDoctrineEntityByDataContainer($dataItem);
            $metadata = $this->objectManager->getClassMetadata(get_class($entity));
            foreach ($properties as $property) {
                $propertyName = $property->getName();

                $normalizePropertyName = Inflector::camelize($propertyName);

                $setter = 'set' . Inflector::classify($propertyName);

                $typeField = $metadata->getTypeOfField($normalizePropertyName);

                $value = $this->handleTypeConversions($property->getValue(), $typeField);

                try {
                    $metadata->getReflectionClass()->getMethod($setter)->invoke($entity, $value);
                } catch (\Exception $e) {
                    throw new Exception\InvalidSetValueException($e->getMessage(), $e->getCode(), $e);
                }
            }
        }
    }

    /**
     * Преобразование типов
     *
     * @param  mixed  $value
     * @param  string $typeOfField
     *
     * @return mixed
     */
    protected function handleTypeConversions($value, $typeOfField)
    {
        switch ($typeOfField) {
            case 'datetimetz':
            case 'datetime':
            case 'time':
            case 'date':
                if (is_int($value)) {
                    $dateTime = new DateTime();
                    $dateTime->setTimestamp($value);
                    $value = $dateTime;
                } elseif (is_string($value)) {
                    $value = '' === $value ? new DateTime() : new DateTime($value);
                }
                break;
            default:
        }

        return $value;
    }


    /**
     * Проставляет связи между сущностями
     *
     * @return void
     * @throws \Nnx\JmsSerializerModule\DataContainer\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     */
    protected function hydrateAssociation()
    {
        $index = $this->dataContainer->getIndex();
        $dataItems = $index->getEntities();
        foreach ($dataItems as $dataItem) {
            if (!$this->metadata->hasAssociationsForEntity($dataItem)) {
                continue;
            }

            $associations = $this->metadata->getAssociationsForEntity($dataItem);
            $entity = $this->getDoctrineEntityByDataContainer($dataItem);

            $entityClassName = get_class($entity);
            $entityMetadata = $this->objectManager->getClassMetadata($entityClassName);

            $rClass = $entityMetadata->getReflectionClass();
            foreach ($associations as $associationName => $associationDataContainers) {
                if ($entityMetadata->isCollectionValuedAssociation($associationName)) {
                    $getter = 'get' . Inflector::classify($associationName);
                    $collection = $rClass->getMethod($getter)->invoke($entity);

                    if (!$collection instanceof Collection) {
                        $errMsg = sprintf('Property %s in entity %s not collection', $associationName, $entityClassName);
                        throw new Exception\RuntimeException($errMsg);
                    }

                    foreach ($associationDataContainers as $associationDataContainerId) {
                        $associationDataContainer = $index->getEntityById($associationDataContainerId);
                        $associationEntity = $this->getDoctrineEntityByDataContainer($associationDataContainer);
                        $collection->add($associationEntity);
                    }
                } elseif ($entityMetadata->isSingleValuedAssociation($associationName)) {
                    $setter = 'set' . Inflector::classify($associationName);

                    $associationDataContainerId = current($associationDataContainers);
                    $associationDataContainer = $index->getEntityById($associationDataContainerId);
                    $associationEntity = $this->getDoctrineEntityByDataContainer($associationDataContainer);

                    $rClass->getMethod($setter)->invoke($entity, $associationEntity);
                }
            }
        }
    }

    /**
     * Инциализация сущностей
     *
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     * @throws \UnexpectedValueException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     */
    protected function initEntities()
    {
        $dataItems = $this->dataContainer->getIndex()->getEntities();
        foreach ($dataItems as $dataItem) {
            $this->initEntity($dataItem);
        }
    }


    /**
     * Инциализация сущности
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @throws \UnexpectedValueException
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     */
    protected function initEntity(DataContainer\EntityInterface $dataItem)
    {
        if ($this->isCandidateForSearchInDb($dataItem)) {
            $this->initEntityFromPersistenceStorage($dataItem);
        } else {
            $this->createEntity($dataItem);
        }
    }

    /**
     * Определяет нужно ли пытаться создать сущност заново, или необходим поиск в хранилище
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return bool
     */
    protected function isCandidateForSearchInDb(DataContainer\EntityInterface $dataItem)
    {
        $isCandidateForSearchInDb = $this->isFindById($dataItem);
        if (false === $isCandidateForSearchInDb) {
            $isCandidateForSearchInDb = 0 === count($dataItem->getAssociations());
        }

        return $isCandidateForSearchInDb;
    }

    /**
     * Проверяет можно ли искать сущность в хранилище по id
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return bool
     */
    protected function isFindById(DataContainer\EntityInterface $dataItem)
    {
        $entityClassName = $this->metadata->getEntityClassNameByDataContainer($dataItem);
        $entityMetadata = $this->objectManager->getClassMetadata($entityClassName);

        $identifierFieldNames = $entityMetadata->getIdentifierFieldNames();
        $isFundById = true;

        $properties = $dataItem->getProperties();
        foreach ($identifierFieldNames as $identifierFieldName) {
            if (!array_key_exists($identifierFieldName, $properties)) {
                $isFundById = false;
                break;
            }
        }

        return $isFundById;
    }

    /**
     * Подготавливает список id для поиска
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return array
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     */
    protected function buildSearchByIdCriteria(DataContainer\EntityInterface $dataItem)
    {
        $entityClassName = $this->metadata->getEntityClassNameByDataContainer($dataItem);
        $entityMetadata = $this->objectManager->getClassMetadata($entityClassName);

        $identifierFieldNames = $entityMetadata->getIdentifierFieldNames();
        $idValueList = [];

        $properties = $dataItem->getProperties();
        foreach ($identifierFieldNames as $identifierFieldName) {
            if (!array_key_exists($identifierFieldName, $properties)) {
                $errMsg = sprintf('Not find value for property %s from %s', $identifierFieldName, $entityClassName);
                throw new Exception\RuntimeException($errMsg);
            }
            $property = $properties[$identifierFieldName];
            $idValueList[$identifierFieldName] = $property->getValue();
        }

        return $idValueList;
    }
    /**
     * Ищет сущность в хранилище
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     * @throws \UnexpectedValueException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     */
    protected function initEntityFromPersistenceStorage(DataContainer\EntityInterface $dataItem)
    {
        $entityClassName = $this->metadata->getEntityClassNameByDataContainer($dataItem);
        $entityRepository = $this->objectManager->getRepository($entityClassName);


        $findEntities = [];
        if ($this->isFindById($dataItem)) {
            $searchByIdCriteria = $this->buildSearchByIdCriteria($dataItem);
            $entity = $entityRepository->find($searchByIdCriteria);
            if (null !== $entity) {
                $findEntities[] = $entity;
            }
        } else {
            $searchCriteria = $this->buildSearchCriteria($dataItem);
            $findEntities = $entityRepository->findBy($searchCriteria);
        }

        $countFindEntities = count($findEntities);
        if ($countFindEntities > 1) {
            $errMsg = 'Found more than one entity';
            throw new Exception\RuntimeException($errMsg);
        }

        if (1 === $countFindEntities) {
            $this->dataContainerIdToDoctrineEntity[$dataItem->getId()] = array_pop($findEntities);
        } else {
            $this->createEntity($dataItem);
        }
    }

    /**
     * @param DataContainer\EntityInterface $dataItem
     *
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     */
    protected function createEntity(DataContainer\EntityInterface $dataItem)
    {
        $entityClassName = $this->metadata->getEntityClassNameByDataContainer($dataItem);

        $entity = $this->getEntityLocator()->get($entityClassName);

        $this->objectManager->persist($entity);

        $this->dataContainerIdToDoctrineEntity[$dataItem->getId()] = $entity;
    }

    /**
     * Получает сущность, которая соответствует контейнеру с данными
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return mixed
     * @throws \Nnx\JmsSerializerModule\DoctrineObjectEngine\Exception\RuntimeException
     */
    public function getDoctrineEntityByDataContainer(DataContainer\EntityInterface $dataItem)
    {
        $dataItemId = $dataItem->getId();
        if (!array_key_exists($dataItemId, $this->dataContainerIdToDoctrineEntity)) {
            $errMsg = sprintf('Doctrine entity not found for data container: id# %s', $dataItemId);
            throw new Exception\RuntimeException($errMsg);
        }

        return $this->dataContainerIdToDoctrineEntity[$dataItemId];
    }

    /**
     * Подготовка критериев для поиска в базе данных
     *
     * @param DataContainer\EntityInterface $dataItem
     *
     * @return array
     */
    protected function buildSearchCriteria(DataContainer\EntityInterface $dataItem)
    {
        $searchCriteria = [];

        foreach ($dataItem->getProperties() as $property) {
            $normalizeName = Inflector::camelize($property->getName());
            $searchCriteria[$normalizeName] = $property->getValue();
        }

        return $searchCriteria;
    }

    /**
     * Устанавливает компонент отвечаюзий за создание сущностей
     *
     * @return ContainerInterface
     */
    public function getEntityLocator()
    {
        return $this->entityLocator;
    }

    /**
     * Возвращает компонент отвечаюзий за создание сущностей
     *
     * @param ContainerInterface $entityLocator
     *
     * @return $this
     */
    public function setEntityLocator(ContainerInterface $entityLocator)
    {
        $this->entityLocator = $entityLocator;

        return $this;
    }
}
