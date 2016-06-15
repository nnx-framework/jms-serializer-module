<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\EventDispatcher;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use ReflectionClass;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;

/**
 * Class EventDispatcherFactory
 *
 * @package Nnx\JmsSerializerModule\EventDispatcher
 */
class EventDispatcherFactory  implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return EventDispatcher
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\EventDispatcher\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $eventDispatcher = new EventDispatcher();
        $creationOptions = $this->getCreationOptions();

        $subscribers = [];
        if (array_key_exists('subscribers', $creationOptions)) {
            if (!is_array($creationOptions['subscribers'])) {
                $errMsg = 'Subscribers for handler registry is not array';
                throw new Exception\RuntimeException($errMsg);
            }
            $subscribers = $creationOptions['subscribers'];
        }

        foreach ($subscribers as $subscriberName) {
            $subscriber = null;
            if (is_string($subscriberName)) {
                if ($serviceLocator->has($subscriberName)) {
                    $subscriber = $serviceLocator->get($subscriberName);
                } elseif (class_exists($subscriberName)) {
                    $r = new ReflectionClass($subscriberName);
                    $subscriber = $r->newInstance();
                }
            }

            if (!$subscriber instanceof EventSubscriberInterface) {
                $errMsg = sprintf(
                    'Subscriber of type %s is invalid; must implement %s',
                    (is_object($subscriber) ? get_class($subscriber) : gettype($subscriber)),
                    EventSubscriberInterface::class
                );
                throw new Exception\RuntimeException($errMsg);
            }
            $eventDispatcher->addSubscriber($subscriber);
        }

        return $eventDispatcher;
    }
}
