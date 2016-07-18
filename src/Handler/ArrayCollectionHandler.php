<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Handler;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\Handler\ArrayCollectionHandler as JmsArrayCollectionHandler;

/**
 * Class ArrayCollectionHandler
 *
 * @package Nnx\JmsSerializerModule\Handler
 */
class ArrayCollectionHandler extends JmsArrayCollectionHandler
{
    /**
     * Поддерживаемые форматы
     *
     * @var array
     */
    protected static $formats = [
        'json',
        'xml',
        'yml',
        'xmlForDoctrine'
    ];

    /**
     * Поддерживаемые форматы
     *
     * @var array
     */
    protected static $collectionTypes = [
        'ArrayCollection',
        'Doctrine\Common\Collections\ArrayCollection',
        'Doctrine\ORM\PersistentCollection',
        'Doctrine\ODM\MongoDB\PersistentCollection',
        'Doctrine\ODM\PHPCR\PersistentCollection',
    ];

    /**
     * @inheritdoc
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $methods = [];

        foreach (static::$collectionTypes as $type) {
            foreach (static::$formats as $format) {
                $methods[] = array(
                    'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                    'type' => $type,
                    'format' => $format,
                    'method' => 'serializeCollection',
                );

                $methods[] = array(
                    'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                    'type' => $type,
                    'format' => $format,
                    'method' => 'deserializeCollection',
                );
            }
        }

        return $methods;
    }

    /**
     * Десериализация коллекции работает некорректно. Описание
     * @see https://github.com/schmittjoh/serializer/issues/9
     *
     * @inheritdoc
     *
     * @param VisitorInterface $visitor
     * @param                  $data
     * @param array            $type
     * @param Context          $context
     *
     * @return ArrayCollection
     */
    public function deserializeCollection(VisitorInterface $visitor, $data, array $type, Context $context)
    {
        $type['name'] = 'array';

        $arrayResult = $visitor->visitArray($data, $type, $context);
        $collection = new ArrayCollection($arrayResult);
        $visitor->setNavigator($context->getNavigator());

        return $collection;
    }
}
