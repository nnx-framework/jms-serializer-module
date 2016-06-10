<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class PluginOptions
 *
 * @package Nnx\JmsSerializerModule\Options
 */
class PluginOptions extends AbstractOptions
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
    protected $options;

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
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Устанавливает настройки плагина
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }
}
