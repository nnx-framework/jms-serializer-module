<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Options;

use Zend\Stdlib\AbstractOptions;


/**
 * Class SerializerOptions
 *
 * @package Nnx\JmsSerializerModule\Options
 */
class SerializerOptions extends AbstractOptions
{
    /**
     * Имя сервиса возвращающего фабрику метаданных для сериалайзера
     *
     * @var string
     */
    protected $metadataFactory;

    /**
     * Имя сервиса хранилища обработчиков
     *
     * @var string
     */
    protected $handlerRegistry;

    /**
     * Имя сервиса возращающего конструктор объектов
     *
     * @var string
     */
    protected $objectConstructor;

    /**
     * Имя сервиса возращающего компонент отвечающий за преобразование типов при сериализации данных
     *
     * @var string
     */
    protected $serializationVisitors;

    /**
     * Имя сервиса возращающего компонент отвечающий за преобразование типов при десириализации данных
     *
     * @var string
     */
    protected $deserializationVisitors;

    /**
     * Имя сервиса отвечающего за диспетчирезацию событий
     *
     * @var string
     */
    protected $dispatcher;

    /**
     * Имя сервиса отвечающего за парсинг типов данных
     *
     * @var string
     */
    protected $typeParser;

    /**
     * Возвращает имя сервиса возвращающего фабрику метаданных для сериалайзера
     *
     * @return string
     */
    public function getMetadataFactory()
    {
        return $this->metadataFactory;
    }

    /**
     * Устанавливает имя сервиса возвращающего фабрику метаданных для сериалайзера
     *
     * @param string $metadataFactory
     *
     * @return $this
     */
    public function setMetadataFactory($metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;

        return $this;
    }

    /**
     * Возвращает имя сервиса хранилища обработчиков
     *
     * @return string
     */
    public function getHandlerRegistry()
    {
        return $this->handlerRegistry;
    }

    /**
     * Устанавливает имя сервиса хранилища обработчиков
     *
     * @param string $handlerRegistry
     *
     * @return $this
     */
    public function setHandlerRegistry($handlerRegistry)
    {
        $this->handlerRegistry = $handlerRegistry;

        return $this;
    }

    /**
     * Возвращает имя сервиса возращающего конструктор объектов
     *
     * @return string
     */
    public function getObjectConstructor()
    {
        return $this->objectConstructor;
    }

    /**
     * Устанавливает имя сервиса возращающего конструктор объектов
     *
     * @param string $objectConstructor
     *
     * @return $this
     */
    public function setObjectConstructor($objectConstructor)
    {
        $this->objectConstructor = $objectConstructor;

        return $this;
    }

    /**
     * Возвращает имя сервиса возращающего компонент отвечающий за преобразование типов при сериализации данных
     *
     * @return string
     */
    public function getSerializationVisitors()
    {
        return $this->serializationVisitors;
    }

    /**
     * Устанавливает имя сервиса возращающего компонент отвечающий за преобразование типов при сериализации данных
     *
     * @param string $serializationVisitors
     *
     * @return $this
     */
    public function setSerializationVisitors($serializationVisitors)
    {
        $this->serializationVisitors = $serializationVisitors;

        return $this;
    }

    /**
     * Возвращает имя сервиса возращающего компонент отвечающий за преобразование типов при десириализации данных
     *
     * @return string
     */
    public function getDeserializationVisitors()
    {
        return $this->deserializationVisitors;
    }

    /**
     * Устанавливает имя сервиса возращающего компонент отвечающий за преобразование типов при десириализации данных
     *
     * @param string $deserializationVisitors
     *
     * @return $this
     */
    public function setDeserializationVisitors($deserializationVisitors)
    {
        $this->deserializationVisitors = $deserializationVisitors;

        return $this;
    }

    /**
     * Возвращает имя сервиса отвечающего за диспетчирезацию событий
     *
     * @return string
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Устанавливает имя сервиса отвечающего за диспетчирезацию событий
     *
     * @param string $dispatcher
     *
     * @return $this
     */
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Возвращает имя сервиса отвечающего за парсинг типов данных
     *
     * @return string
     */
    public function getTypeParser()
    {
        return $this->typeParser;
    }

    /**
     * Устанавливает имя сервиса отвечающего за парсинг типов данных
     *
     * @param string $typeParser
     *
     * @return $this
     */
    public function setTypeParser($typeParser)
    {
        $this->typeParser = $typeParser;

        return $this;
    }
}
