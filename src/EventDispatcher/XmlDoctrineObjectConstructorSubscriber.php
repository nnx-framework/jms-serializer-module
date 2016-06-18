<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\EventDispatcher;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\XmlDeserializationVisitor;
use SimpleXMLElement;
use Nnx\JmsSerializerModule\DataContainerBuilder\XmlBuilderInterface;
use Nnx\JmsSerializerModule\ObjectConstructor\DoctrineObjectConstructor\DataInterface;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * Class XmlDoctrineObjectConstructorSubscriber
 *
 * @package Nnx\JmsSerializerModule\EventDispatcher
 */
class XmlDoctrineObjectConstructorSubscriber implements EventSubscriberInterface
{

    /**
     * Хранилище ObjectManager'ов Doctrine 2
     *
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * Сервис для подготовки данных из SimpleXMLElement
     *
     * @var XmlBuilderInterface
     */
    protected $dataContainerFromXmlBuilder;

    /**
     * Менеджер для получения контейнера с данными для DoctrineObjectConstructor
     *
     * @var ContainerInterface
     */
    protected $doctrineObjectConstructorDataManager;

    /**
     * XmlDoctrineObjectConstructorSubscriber constructor.
     *
     * @param ManagerRegistry     $managerRegistry
     * @param XmlBuilderInterface $dataContainerFromXmlBuilder
     * @param ContainerInterface  $doctrineObjectConstructorDataManager
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        XmlBuilderInterface $dataContainerFromXmlBuilder,
        ContainerInterface $doctrineObjectConstructorDataManager
    ) {
        $this->setDoctrineObjectConstructorDataManager($doctrineObjectConstructorDataManager);
        $this->setManagerRegistry($managerRegistry);
        $this->setDataContainerFromXmlBuilder($dataContainerFromXmlBuilder);
    }


    /**
     * Обработка события бросаемого перед десериализацией данных
     *
     * @param PreDeserializeEvent $event
     *
     * @return void
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Interop\Container\Exception\NotFoundException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        if (!$this->isToHandleEvent($event)) {
            return;
        }

        $visitor = $event->getVisitor();
        if (!$visitor instanceof XmlDeserializationVisitor) {
            return;
        }


        /** @var SimpleXMLElement $data */
        $data = $event->getData();

        $dataContainerFromXmlBuilder = $this->getDataContainerFromXmlBuilder();

        if (!$dataContainerFromXmlBuilder->hasDataInCache($data)) {
            $this->getDataContainerFromXmlBuilder()->loadDataFromResource($data);
        }

        $dataContainer = $dataContainerFromXmlBuilder->getDataContainerFromCache($data);
        $entity = $dataContainerFromXmlBuilder->getEntityFromCache($data);


        $dataForDoctrineObjectConstructor = $this->getDoctrineObjectConstructorDataManager()->get(
            DataInterface::class,
            [
                'dataContainer' => $dataContainer,
                'entity' => $entity
            ]
        );

        $event->setData($dataForDoctrineObjectConstructor);
    }


    /**
     * Проверяет, нужно ли обрабатывать данное событие
     *
     * @param PreDeserializeEvent $event
     *
     * @return false
     */
    public function isToHandleEvent(PreDeserializeEvent $event)
    {
        $type = $event->getType();
        if (!array_key_exists('name', $type)) {
            return false;
        }
        $class = $type['name'];
        if (!class_exists($class)) {
            return false;
        }

        $managerRegistry = $this->getManagerRegistry();
        $objectManager = $managerRegistry->getManagerForClass($class);
        if (!$objectManager instanceof ObjectManager) {
            return false;
        }

        $data = $event->getData();

        return $data instanceof SimpleXMLElement;
    }


    /**
     * @inheritdoc
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event'  => Events::PRE_DESERIALIZE,
                'format' => 'xml',
                'method' => 'onPreDeserialize'
            ],
        ];
    }

    /**
     * Возвращает хранилище ObjectManager'ов Doctrine 2
     *
     * @return ManagerRegistry
     */
    public function getManagerRegistry()
    {
        return $this->managerRegistry;
    }

    /**
     * Устанавливает хранилище ObjectManager'ов Doctrine 2
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
     * Возвращает сервис для подготовки данных из SimpleXMLElement
     *
     * @return XmlBuilderInterface
     */
    public function getDataContainerFromXmlBuilder()
    {
        return $this->dataContainerFromXmlBuilder;
    }

    /**
     * Устанавливает сервис для подготовки данных из SimpleXMLElement
     *
     * @param XmlBuilderInterface $dataContainerFromXmlBuilder
     *
     * @return $this
     */
    public function setDataContainerFromXmlBuilder(XmlBuilderInterface $dataContainerFromXmlBuilder)
    {
        $this->dataContainerFromXmlBuilder = $dataContainerFromXmlBuilder;

        return $this;
    }

    /**
     * Возвращает менеджер для получения контейнера с данными для DoctrineObjectConstructor
     *
     * @return AbstractPluginManager
     */
    public function getDoctrineObjectConstructorDataManager()
    {
        return $this->doctrineObjectConstructorDataManager;
    }

    /**
     * Устанавливает менеджер для получения контейнера с данными для DoctrineObjectConstructor
     *
     * @param ContainerInterface $doctrineObjectConstructorDataManager
     *
     * @return $this
     */
    public function setDoctrineObjectConstructorDataManager(ContainerInterface $doctrineObjectConstructorDataManager)
    {
        $this->doctrineObjectConstructorDataManager = $doctrineObjectConstructorDataManager;

        return $this;
    }
}
