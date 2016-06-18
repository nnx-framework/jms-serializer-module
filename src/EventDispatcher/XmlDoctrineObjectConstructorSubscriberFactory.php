<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\EventDispatcher;

use Interop\Container\ContainerInterface;
use Nnx\JmsSerializerModule\DataContainerBuilder\XmlBuilderInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class XmlDoctrineObjectConstructorSubscriberFactory
 *
 * @package Nnx\JmsSerializerModule\EventDispatcher
 */
class XmlDoctrineObjectConstructorSubscriberFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return XmlDoctrineObjectConstructorSubscriber
     * @throws \Interop\Container\Exception\NotFoundException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ContainerInterface $serviceLocator */
        /** @var ManagerRegistry $managerRegistry */
        $managerRegistry = $serviceLocator->get(ManagerRegistry::class);

        /** @var XmlBuilderInterface $dataContainerFromXmlBuilder */
        $dataContainerFromXmlBuilder = $serviceLocator->get(XmlBuilderInterface::class);

        return new XmlDoctrineObjectConstructorSubscriber($managerRegistry, $dataContainerFromXmlBuilder, $serviceLocator);
    }
}
