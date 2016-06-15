<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class SerializerPluginOptions
 *
 * @package Nnx\JmsSerializerModule\Options
 */
class SerializerPluginOptions extends AbstractOptions
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
     * Настройки для сериалайзера
     *
     * @var SerializerOptions
     */
    protected $serializerOptions;

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
     * @return SerializerOptions
     */
    public function getOptions()
    {
        if (null === $this->serializerOptions) {
            $this->serializerOptions = new SerializerOptions($this->options);
        }

        return $this->serializerOptions;
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
