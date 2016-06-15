<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Options;

use Zend\Stdlib\AbstractOptions;
use Metadata\ClassHierarchyMetadata;


/**
 * Class MetadataFactoryOptions
 *
 * @package Nnx\JmsSerializerModule\Options
 */
class MetadataFactoryOptions extends AbstractOptions
{
    /**
     * Имя для получения драйвера метаданнх
     *
     * @var string
     */
    protected $metadataDriver;

    /**
     * @todo добавить корректное описание для класса
     *
     * @var string
     */
    protected $hierarchyMetadataClass = ClassHierarchyMetadata::class;

    /**
     * Флаг отдадки
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * Возвращает имя для получения драйвера метаданнх
     *
     * @return string
     */
    public function getMetadataDriver()
    {
        return $this->metadataDriver;
    }

    /**
     * Устанавливает имя для получения драйвера метаданнх
     *
     * @param string $metadataDriver
     *
     * @return $this
     */
    public function setMetadataDriver($metadataDriver)
    {
        $this->metadataDriver = (string)$metadataDriver;

        return $this;
    }

    /**
     * @return string
     */
    public function getHierarchyMetadataClass()
    {
        return $this->hierarchyMetadataClass;
    }

    /**
     * @param string $hierarchyMetadataClass
     *
     * @return $this
     */
    public function setHierarchyMetadataClass($hierarchyMetadataClass)
    {
        $this->hierarchyMetadataClass = (string)$hierarchyMetadataClass;

        return $this;
    }

    /**
     * Возвращает флаг указывающий на то, нужно ли производить отладку
     *
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Устанавливает флаг отладки
     *
     * @param boolean $debug
     *
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = (boolean)$debug;

        return $this;
    }
}
