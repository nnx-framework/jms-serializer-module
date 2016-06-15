<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\NamingStrategy;

use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IdenticalPropertyNamingStrategyFactory
 *
 * @package Nnx\JmsSerializerModule\NamingStrategy
 */
class IdenticalPropertyNamingStrategyFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return IdenticalPropertyNamingStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new IdenticalPropertyNamingStrategy();
    }
}
