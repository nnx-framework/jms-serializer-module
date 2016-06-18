<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\MetadataDriver;

use Doctrine\Common\Persistence\ManagerRegistry;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use JMS\Serializer\Metadata\Driver\AnnotationDriver;
use JMS\Serializer\Metadata\Driver\DoctrineTypeDriver;
use Metadata\Driver\DriverInterface;


/**
 * Class DoctrineTypeDriverFactory
 *
 * @package Nnx\JmsSerializerModule\MetadataDriver
 */
class DoctrineTypeDriverFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return AnnotationDriver
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\MetadataDriver\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        if (!array_key_exists('delegate', $creationOptions)) {
            $errMsg = 'Delegate driver for DoctrineTypeDriver not specified';
            throw new Exception\RuntimeException($errMsg);
        }

        $delegateDriver = $serviceLocator->get($creationOptions['delegate']);

        if (!$delegateDriver instanceof DriverInterface) {
            $errMsg = sprintf('Delegate driver not implement %s', DriverInterface::class);
            throw new Exception\RuntimeException($errMsg);
        }

        if (!array_key_exists('managerRegistry', $creationOptions)) {
            $errMsg = 'Manager registry service name not specified';
            throw new Exception\RuntimeException($errMsg);
        }
        $managerRegistry = $serviceLocator->get($creationOptions['managerRegistry']);

        if (!$managerRegistry instanceof ManagerRegistry) {
            $errMsg = sprintf(
                'Manager registry of type %s is invalid; must implement %s',
                (is_object($managerRegistry) ? get_class($managerRegistry) : gettype($managerRegistry)),
                ManagerRegistry::class
            );
            throw new Exception\RuntimeException($errMsg);
        }



        return new DoctrineTypeDriver($delegateDriver, $managerRegistry);
    }
}
