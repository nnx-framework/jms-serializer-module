<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class MetadataFactoryPluginOptions
 *
 * @package Nnx\JmsSerializerModule\Options
 */
class MetadataFactoryPluginOptions extends AbstractOptions
{
    /**
     * Имя плагина
     *
     * @var string
     */
    protected $name;

    /**
     * Настройки плагина
     *
     * @var array
     */
    protected $options = [];

    /**
     * Настройки для фабрики метаданных
     *
     * @var SerializerOptions
     */
    protected $metadataFactoryOptions;

    /**
     * Возвращает имя плагина
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Устанавливает имя плагина
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Возвращает настройки плагина
     *
     * @return MetadataFactoryOptions
     */
    public function getOptions()
    {
        if (null === $this->metadataFactoryOptions) {
            $this->metadataFactoryOptions = new MetadataFactoryOptions($this->options);
        }

        return $this->metadataFactoryOptions;
    }

    /**
     * Устанавливает настройки плагина
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }
}
