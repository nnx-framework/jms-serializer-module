<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\ObjectConstructor;

use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\DeserializationContext;
use Nnx\JmsSerializerModule\DoctrineObjectEngine\DoctrineObjectEngineInterface;

/**
 * Class DoctrineObjectConstructor
 *
 * @package Nnx\JmsSerializerModule\ObjectConstructor
 */
class DoctrineObjectConstructor implements ObjectConstructorInterface
{
    /**
     * Хранилище ObjectManager'ов Doctrine
     *
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * Конструктор объектов которому делегируется создание объекта из данных, в случае если данные не подходят для данного
     * конструктора
     *
     * @var ObjectConstructorInterface
     */
    protected $fallbackConstructor;

    /**
     * Движок для создания объектов доктрины
     *
     * @var DoctrineObjectEngineInterface
     */
    protected $doctrineObjectEngine;

    /**
     * Информация о уже обработанных конейнерах данных
     *
     * @var array
     */
    protected $finishedDataContainerId = [];

    /**
     * Constructor.
     *
     * @param ManagerRegistry               $managerRegistry     Manager registry
     * @param ObjectConstructorInterface    $fallbackConstructor Fallback object constructor
     * @param DoctrineObjectEngineInterface $doctrineObjectEngine
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        ObjectConstructorInterface $fallbackConstructor,
        DoctrineObjectEngineInterface $doctrineObjectEngine
    ) {
        $this->setManagerRegistry($managerRegistry);
        $this->setFallbackConstructor($fallbackConstructor);
        $this->setDoctrineObjectEngine($doctrineObjectEngine);
    }

    /**
     * @inheritdoc
     *
     * @param VisitorInterface       $visitor
     * @param ClassMetadata          $metadata
     * @param mixed                  $data
     * @param array                  $type
     * @param DeserializationContext $context
     *
     * @return mixed
     */
    public function construct(VisitorInterface $visitor, ClassMetadata $metadata, $data, array $type, DeserializationContext $context)
    {
        $fallbackConstructor = $this->getFallbackConstructor();
        if (!$data instanceof DoctrineObjectConstructor\DataInterface) {
            return $fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        // Locate possible ObjectManager
        $objectManager = $this->getManagerRegistry()->getManagerForClass($metadata->name);

        if (!$objectManager) {
            // No ObjectManager found, proceed with normal deserialization
            return $fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        // Locate possible ClassMetadata
        $classMetadataFactory = $objectManager->getMetadataFactory();

        if ($classMetadataFactory->isTransient($metadata->name)) {
            // No ClassMetadata found, proceed with normal deserialization
            return $fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        $dataContainer = $data->getDataContainer();

        $doctrineObjectEngine = $this->getDoctrineObjectEngine();
        $dataContainerId = $dataContainer->getId();
        if (!array_key_exists($dataContainerId, $this->finishedDataContainerId)) {
            $doctrineObjectEngine->import($dataContainer, $metadata->name, $objectManager);
            $objectManager->flush();
            $this->finishedDataContainerId[$dataContainerId] = $dataContainerId;
        }

        $entity = $data->getEntity();

        return $doctrineObjectEngine->getDoctrineEntityByDataContainer($entity);
    }

    /**
     * Возвращает хранилище ObjectManager'ов Doctrine
     *
     * @return ManagerRegistry
     */
    public function getManagerRegistry()
    {
        return $this->managerRegistry;
    }

    /**
     * Устанавливает хранилище ObjectManager'ов Doctrine
     *
     * @param ManagerRegistry $managerRegistry
     *
     * @return $this
     */
    public function setManagerRegistry(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;

        return $this;
    }

    /**
     * Возвращает конструктор объектов которому делегируется создание объекта из данных, в случае если данные не
     * подходят для данного конструктора
     *
     * @return ObjectConstructorInterface
     */
    public function getFallbackConstructor()
    {
        return $this->fallbackConstructor;
    }

    /**
     * Устанавливает конструктор объектов которому делегируется создание объекта из данных, в случае если данные не
     * подходят для данного конструктора
     *
     * @param ObjectConstructorInterface $fallbackConstructor
     *
     * @return $this
     */
    public function setFallbackConstructor($fallbackConstructor)
    {
        $this->fallbackConstructor = $fallbackConstructor;

        return $this;
    }

    /**
     * Возвращает движок для создания объектов доктрины
     *
     * @return DoctrineObjectEngineInterface
     */
    public function getDoctrineObjectEngine()
    {
        return $this->doctrineObjectEngine;
    }

    /**
     * Устанавливает движок для создания объектов доктрины
     *
     * @param DoctrineObjectEngineInterface $doctrineObjectEngine
     *
     * @return $this
     */
    public function setDoctrineObjectEngine(DoctrineObjectEngineInterface $doctrineObjectEngine)
    {
        $this->doctrineObjectEngine = $doctrineObjectEngine;

        return $this;
    }
}
