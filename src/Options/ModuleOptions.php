<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Options;

use Zend\Stdlib\AbstractOptions;
use Nnx\ModuleOptions\ModuleOptionsInterface;


/**
 * Class ModuleOptions
 *
 * @package Nnx\JmsSerializerModule\Options
 */
class ModuleOptions extends AbstractOptions implements ModuleOptionsInterface
{
    /**
     * Данные для конфига сериалайзеров
     *
     * @var array
     */
    protected $serializers = [];

    /**
     * Конфиги сериалайзеров
     *
     * @var SerializerOptions[]
     */
    protected $serializersOptions = [];

    /**
     * Данные для фабрик метеданных сериалайзеров
     *
     * @var array
     */
    protected $metadataFactories = [];

    /**
     * Конфиги для фабрик метаданных
     *
     * @var PluginOptions[]
     */
    protected $metadataFactoriesOptions = [];

    /**
     * Данные для хранилища хенделеров сериалайзера
     *
     * @var array
     */
    protected $handlerRegistries = [];

    /**
     * Хранилища хенделеров сериалайзера
     *
     * @var PluginOptions[]
     */
    protected $handlerRegistriesOptions = [];

    /**
     * Данные описывающие драйверы метаданных
     *
     * @var array
     */
    protected $metadataDrivers = [];

    /**
     * Конфиги драйверов метаданных
     *
     * @var PluginOptions[]
     */
    protected $metadataDriversOptions = [];

    /**
     * Данные описывающие конструкторы объектов
     *
     * @var array
     */
    protected $objectConstructors = [];

    /**
     * Конфиги конструкторов объектов
     *
     * @var PluginOptions[]
     */
    protected $objectConstructorsOptions = [];

    /**
     * Набор плагинов используемх для сериализации
     *
     * @var array
     */
    protected $serializationVisitors = [];

    /**
     * Набор плагинов используемх для десериализации
     *
     * @var array
     */
    protected $deserializationVisitors = [];

    /**
     * Набор данных описывающих диспетчеры событий
     *
     * @var array
     */
    protected $eventDispatchers = [];

    /**
     * Устанавливает конфиги описывающие диспетчеры событий
     *
     * @var array
     */
    protected $eventDispatchersOptions = [];


    /**
     * Набор данных описывающих парсеры типов
     *
     * @var array
     */
    protected $typeParsers = [];

    /**
     * Устанавливает конфиги описывающие парсеры типов данных
     *
     * @var array
     */
    protected $typeParsersOptions = [];

    /**
     * Устанавливает информацию о сериалайзерах
     *
     * @param array $serializer
     *
     * @return $this
     */
    public function setSerializers(array $serializer)
    {
        $this->serializers = $serializer;
        $this->serializersOptions = [];

        return $this;
    }

    /**
     * Определяет есть ли сериалайзер с заданным именем
     *
     * @param string $serializerName
     *
     * @return $this
     */
    public function hasSerializer($serializerName)
    {
        return array_key_exists($serializerName, $this->serializers);
    }

    /**
     * Возвращает конфиг сериалайзера с заданным именем
     *
     * @param $serializerName
     *
     * @return SerializerOptions
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function getSerializer($serializerName)
    {
        if (array_key_exists($serializerName, $this->serializersOptions)) {
            return $this->serializersOptions[$serializerName];
        }

        if (!$this->hasSerializer($serializerName)) {
            $errMsg = sprintf('Config for serializer %s not found', $serializerName);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $this->serializersOptions[$serializerName] = new SerializerOptions($this->serializers[$serializerName]);

        return $this->serializersOptions[$serializerName];
    }

    /**
     * Устанавливает данные для фабрик метеданных сериалайзеров
     *
     * @param array $metadataFactories
     *
     * @return $this
     */
    public function setMetadataFactories(array $metadataFactories)
    {
        $this->metadataFactories = $metadataFactories;
        $this->metadataFactoriesOptions = [];

        return $this;
    }


    /**
     * Определяет есть ли фабрика метаданных с заданныим именем
     *
     * @param string $metadataFactoryName
     *
     * @return $this
     */
    public function hasMetadataFactory($metadataFactoryName)
    {
        return array_key_exists($metadataFactoryName, $this->metadataFactories);
    }

    /**
     * Возвращает конфиг фабрики метаданных с заданным именем
     *
     * @param $metadataFactoryName
     *
     * @return PluginOptions
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function gasMetadataFactory($metadataFactoryName)
    {
        if (array_key_exists($metadataFactoryName, $this->metadataFactoriesOptions)) {
            return $this->metadataFactoriesOptions[$metadataFactoryName];
        }

        if (!$this->hasMetadataFactory($metadataFactoryName)) {
            $errMsg = sprintf('Config for metadata factory %s not found', $metadataFactoryName);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $this->metadataFactoriesOptions[$metadataFactoryName] = new PluginOptions($this->metadataFactories[$metadataFactoryName]);

        return $this->metadataFactoriesOptions[$metadataFactoryName];
    }

    /**
     * Устанавливает данные для хранилища хенделеров сериалайзера
     *
     * @param array $handlerRegistries
     *
     * @return $this
     */
    public function setHandlerRegistries(array $handlerRegistries)
    {
        $this->handlerRegistries = $handlerRegistries;
        $this->handlerRegistriesOptions = [];

        return $this;
    }

    /**
     * Определяет есть ли хранилище хенделеров сериалайзера с заданныим именем
     *
     * @param string $handlerRegistryName
     *
     * @return $this
     */
    public function hasHandlerRegistry($handlerRegistryName)
    {
        return array_key_exists($handlerRegistryName, $this->handlerRegistries);
    }

    /**
     * Возвращает конфиг хранилища хенделеров сериалайзера с заданныим именем
     *
     * @param $handlerRegistryName
     *
     * @return PluginOptions
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function getHandlerRegistry($handlerRegistryName)
    {
        if (array_key_exists($handlerRegistryName, $this->handlerRegistriesOptions)) {
            return $this->handlerRegistriesOptions[$handlerRegistryName];
        }

        if (!$this->hasHandlerRegistry($handlerRegistryName)) {
            $errMsg = sprintf('Handler registry %s not found', $handlerRegistryName);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $this->handlerRegistriesOptions[$handlerRegistryName] = new PluginOptions($this->handlerRegistriesOptions[$handlerRegistryName]);

        return $this->handlerRegistriesOptions[$handlerRegistryName];
    }

    /**
     * Устанавливает данные описывающие драйверы метаданных
     *
     * @param array $metadataDrivers
     *
     * @return $this
     */
    public function setMetadataDrivers(array $metadataDrivers)
    {
        $this->metadataDrivers = $metadataDrivers;
        $this->metadataDriversOptions = [];

        return $this;
    }


    /**
     * Проверяет есть ли драйвер метаданных с заданным именем
     *
     * @param string $metadataDriverName
     *
     * @return $this
     */
    public function hasMetadataDriver($metadataDriverName)
    {
        return array_key_exists($metadataDriverName, $this->metadataDrivers);
    }

    /**
     * Возвращает конфиг драйвера метаданных по заданному имени
     *
     * @param $metadataDriverName
     *
     * @return PluginOptions
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function getMetadataDriver($metadataDriverName)
    {
        if (array_key_exists($metadataDriverName, $this->metadataDriversOptions)) {
            return $this->metadataDriversOptions[$metadataDriverName];
        }

        if (!$this->hasMetadataDriver($metadataDriverName)) {
            $errMsg = sprintf('Metadata driver %s not found', $metadataDriverName);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $this->metadataDriversOptions[$metadataDriverName] = new PluginOptions($this->metadataDrivers[$metadataDriverName]);

        return $this->metadataDriversOptions[$metadataDriverName];
    }

    /**
     * Устанавливает данные описывающие конструкторы объектов
     *
     * @param array $objectConstructors
     *
     * @return $this
     */
    public function setObjectConstructors(array $objectConstructors)
    {
        $this->objectConstructors = $objectConstructors;
        $this->objectConstructorsOptions = [];

        return $this;
    }


    /**
     * Проверяет есть конструктор объектов с заданным именем
     *
     * @param string $objectConstructorName
     *
     * @return $this
     */
    public function hasObjectConstructor($objectConstructorName)
    {
        return array_key_exists($objectConstructorName, $this->objectConstructors);
    }

    /**
     * Возвращает конструктор объектов по заданному имени
     *
     * @param $objectConstructorName
     *
     * @return PluginOptions
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function getObjectConstructor($objectConstructorName)
    {
        if (array_key_exists($objectConstructorName, $this->objectConstructorsOptions)) {
            return $this->objectConstructorsOptions[$objectConstructorName];
        }

        if (!$this->hasObjectConstructor($objectConstructorName)) {
            $errMsg = sprintf('Object constructor %s not found', $objectConstructorName);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $this->objectConstructorsOptions[$objectConstructorName] = new PluginOptions($this->objectConstructors[$objectConstructorName]);

        return $this->objectConstructorsOptions[$objectConstructorName];
    }

    /**
     * Устанавливает набор плагинов используемх для сериализации
     *
     * @param array $serializationVisitors
     *
     * @return $this
     */
    public function setSerializationVisitors(array $serializationVisitors)
    {
        $this->serializationVisitors = $serializationVisitors;

        return $this;
    }


    /**
     * Проверяет по указанному имени, наличие набора плагинов используемых для сериализации данных
     *
     * @param string $serializationVisitorName
     *
     * @return $this
     */
    public function hasSerializationVisitor($serializationVisitorName)
    {
        return array_key_exists($serializationVisitorName, $this->serializationVisitors);
    }


    /**
     * Возвращает по указанному имени, набор плагинов используемых для сериализации данных
     *
     * @param $serializationVisitorName
     *
     * @return PluginOptions
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function getSerializationVisitor($serializationVisitorName)
    {
        if (!$this->hasSerializationVisitor($serializationVisitorName)) {
            $errMsg = sprintf('Invalid serialization visitor name %s not found', $serializationVisitorName);
            throw new Exception\InvalidArgumentException($errMsg);
        }
        return $this->serializationVisitors[$serializationVisitorName];
    }

    /**
     * Возвращает набор плагинов используемых для десериализации
     *
     * @param array $deserializationVisitors
     *
     * @return $this
     */
    public function setDeserializationVisitors(array $deserializationVisitors)
    {
        $this->deserializationVisitors = $deserializationVisitors;

        return $this;
    }


    /**
     * Проверяет по указанному имени, наличие набора плагинов используемых для десериализации данных
     *
     * @param string $deserializationVisitorName
     *
     * @return $this
     */
    public function hasDeserializationVisitor($deserializationVisitorName)
    {
        return array_key_exists($deserializationVisitorName, $this->deserializationVisitors);
    }


    /**
     * Возвращает по указанному имени, набор плагинов используемых для десериализации данных
     *
     * @param $deserializationVisitorName
     *
     * @return PluginOptions
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function getDeserializationVisitor($deserializationVisitorName)
    {
        if (!$this->hasDeserializationVisitor($deserializationVisitorName)) {
            $errMsg = sprintf('Invalid deserialization visitor name %s not found', $deserializationVisitorName);
            throw new Exception\InvalidArgumentException($errMsg);
        }
        return $this->deserializationVisitors[$deserializationVisitorName];
    }

    /**
     * Устанавливает набор данных описывающих диспетчеры событий
     *
     * @param array $eventDispatchers
     *
     * @return $this
     */
    public function setEventDispatchers(array $eventDispatchers)
    {
        $this->eventDispatchers = $eventDispatchers;
        $this->eventDispatchersOptions = [];

        return $this;
    }

    /**
     * Проверяет есть ли диспетчер событий с заданным именем
     *
     * @param string $eventDispatcherName
     *
     * @return $this
     */
    public function hasEventDispatcher($eventDispatcherName)
    {
        return array_key_exists($eventDispatcherName, $this->eventDispatchers);
    }

    /**
     * Возвращает диспетчер событий по заданному имени
     *
     * @param $eventDispatcherName
     *
     * @return PluginOptions
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function getEventDispatcher($eventDispatcherName)
    {
        if (array_key_exists($eventDispatcherName, $this->eventDispatchersOptions)) {
            return $this->eventDispatchersOptions[$eventDispatcherName];
        }

        if (!$this->hasEventDispatcher($eventDispatcherName)) {
            $errMsg = sprintf('Event dispatcher %s not found', $eventDispatcherName);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $this->eventDispatchersOptions[$eventDispatcherName] = new PluginOptions($this->eventDispatchers[$eventDispatcherName]);

        return $this->eventDispatchersOptions[$eventDispatcherName];
    }

    /**
     * Устанавливает набор данных описывающих парсеры типов
     *
     * @param array $typeParsers
     *
     * @return $this
     */
    public function setTypeParsers(array $typeParsers)
    {
        $this->typeParsers = $typeParsers;
        $this->typeParsersOptions = [];

        return $this;
    }


    /**
     * Проверяет есть ли парсер типов  с заданным именем
     *
     * @param string $typeParserName
     *
     * @return $this
     */
    public function hasTypeParser($typeParserName)
    {
        return array_key_exists($typeParserName, $this->typeParsers);
    }

    /**
     * Возвращает парсер типов с заданным именем
     *
     * @param $typeParserName
     *
     * @return PluginOptions
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function getTypeParser($typeParserName)
    {
        if (array_key_exists($typeParserName, $this->typeParsersOptions)) {
            return $this->typeParsersOptions[$typeParserName];
        }

        if (!$this->hasTypeParser($typeParserName)) {
            $errMsg = sprintf('Type parser %s not found', $typeParserName);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $this->typeParsersOptions[$typeParserName] = new PluginOptions($this->typeParsers[$typeParserName]);

        return $this->typeParsersOptions[$typeParserName];
    }
}
