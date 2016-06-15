<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\HandlerRegistry;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use ReflectionClass;

/**
 * Class HandlerRegistryFactory
 *
 * @package Nnx\JmsSerializerModule\HandlerRegistry
 */
class HandlerRegistryFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return HandlerRegistry
     * @throws \Nnx\JmsSerializerModule\HandlerRegistry\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\MetadataDriver\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $handlerRegistry = new HandlerRegistry();
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

            if (!$subscriber instanceof SubscribingHandlerInterface) {
                $errMsg = sprintf(
                    'Subscriber of type %s is invalid; must implement %s',
                    (is_object($subscriber) ? get_class($subscriber) : gettype($subscriber)),
                    SubscribingHandlerInterface::class
                );
                throw new Exception\RuntimeException($errMsg);
            }
            $handlerRegistry->registerSubscribingHandler($subscriber);
        }

        return $handlerRegistry;
    }
}
