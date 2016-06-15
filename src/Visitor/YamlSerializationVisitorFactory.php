<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Visitor;

use JMS\Serializer\YamlSerializationVisitor;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class YamlSerializationVisitorFactory
 *
 * @package Nnx\JmsSerializerModule\Visitor
 */
class YamlSerializationVisitorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait, NamingStrategyTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return YamlSerializationVisitor
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $namingStrategy = $this->getNamingStrategyFromContainer($serviceLocator);

        return new YamlSerializationVisitor($namingStrategy);
    }
}
