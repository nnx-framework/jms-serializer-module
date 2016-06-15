<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Serializer;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer as JmsSerializerBase;
use Zend\Serializer\Adapter\AdapterInterface;
use JMS\Serializer\DeserializationContext;

/**
 * Class JmsSerializer
 *
 * @package Nnx\JmsSerializerModule\Serializer
 */
class JmsSerializer extends JmsSerializerBase implements AdapterInterface
{
    /**
     * @param mixed                                 $data
     * @param                                       $format
     * @param SerializationContext|null             $context
     *
     * @return mixed|string
     */
    public function serialize($data, $format = null, SerializationContext $context = null)
    {
        return parent::serialize($data, $format, $context);
    }


    /**
     * Метод обертка
     *
     * @param string                      $serialized
     * @param null                        $type
     * @param null                        $format
     * @param DeserializationContext|null $context
     *
     * @return mixed|void
     */
    public function unserialize($serialized, $type = null, $format = null, DeserializationContext $context = null)
    {
        return $this->deserialize($serialized, $type, $format, $context);
    }
}
