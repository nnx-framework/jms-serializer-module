<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Visitor;

use JMS\Serializer\XmlDeserializationVisitor;
use Nnx\JmsSerializerModule\DataContainerBuilder\XmlBuilderInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class XmlDeserializationForDoctrineVisitorFactory
 *
 * @package Nnx\JmsSerializerModule\Visitor
 */
class XmlDeserializationForDoctrineVisitorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait, NamingStrategyTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return XmlDeserializationVisitor
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $namingStrategy = $this->getNamingStrategyFromContainer($serviceLocator);

        /** @var XmlBuilderInterface $dataContainerFromXmlBuilder */
        $dataContainerFromXmlBuilder = $serviceLocator->get(XmlBuilderInterface::class);

        return new XmlDeserializationForDoctrineVisitor($namingStrategy, $dataContainerFromXmlBuilder);
    }
}
