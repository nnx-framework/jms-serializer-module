<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Visitor;


use JMS\Serializer\GenericDeserializationVisitor;

/**
 * Class JsonDeserializationVisitor
 *
 * @package Nnx\JmsSerializerModule\Visitor
 */
class JsonDeserializationVisitor extends GenericDeserializationVisitor
{
    /**
     * @inheritdoc
     *
     * @param $str
     *
     * @return mixed
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\RuntimeException
     */
    protected function decode($str)
    {
        $decoded = json_decode($str);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $decoded;

            case JSON_ERROR_DEPTH:
                throw new Exception\RuntimeException('Could not decode JSON, maximum stack depth exceeded.');

            case JSON_ERROR_STATE_MISMATCH:
                throw new Exception\RuntimeException('Could not decode JSON, underflow or the nodes mismatch.');

            case JSON_ERROR_CTRL_CHAR:
                throw new Exception\RuntimeException('Could not decode JSON, unexpected control character found.');

            case JSON_ERROR_SYNTAX:
                throw new Exception\RuntimeException('Could not decode JSON, syntax error - malformed JSON.');

            case JSON_ERROR_UTF8:
                throw new Exception\RuntimeException('Could not decode JSON, malformed UTF-8 characters (incorrectly encoded?)');

            default:
                throw new Exception\RuntimeException('Could not decode JSON.');
        }
    }
}
