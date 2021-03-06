<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainer;

/**
 * Class Property
 *
 * @package Nnx\JmsSerializerModule\DataContainer
 */
class Property
{
    /**
     * Имя поля
     *
     * @var string
     */
    protected $name;

    /**
     * Значение поля
     *
     * @var string
     */
    protected $value;

    /**
     * Устанавливает имя поля
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Возвращает имя поля
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
     * Устанавливает значение поля
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Возвращает значение поля
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
