<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Visitor;

use JMS\Serializer\JsonDeserializationVisitor;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class JmsJsonDeserializationVisitorFactory
 *
 * @package Nnx\JmsSerializerModule\Visitor
 */
class JmsJsonDeserializationVisitorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait, NamingStrategyTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return JsonDeserializationVisitor
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $namingStrategy = $this->getNamingStrategyFromContainer($serviceLocator);

        return new JsonDeserializationVisitor($namingStrategy);
    }
}
