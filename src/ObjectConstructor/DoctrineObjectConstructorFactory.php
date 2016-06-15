<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\ObjectConstructor;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\Construction\DoctrineObjectConstructor;

/**
 * Class DoctrineObjectConstructorFactory
 *
 * @package Nnx\JmsSerializerModule\ObjectConstructor
 */
class DoctrineObjectConstructorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return DoctrineObjectConstructor
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\ObjectConstructor\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

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

        if (!array_key_exists('fallbackConstructor', $creationOptions)) {
            $errMsg = 'Fallback constructor name not specified';
            throw new Exception\RuntimeException($errMsg);
        }
        $fallbackConstructor = $serviceLocator->get($creationOptions['fallbackConstructor']);

        if (!$fallbackConstructor instanceof ObjectConstructorInterface) {
            $errMsg = sprintf(
                'Fallback constructor of type %s is invalid; must implement %s',
                (is_object($fallbackConstructor) ? get_class($fallbackConstructor) : gettype($fallbackConstructor)),
                ObjectConstructorInterface::class
            );
            throw new Exception\RuntimeException($errMsg);
        }

        return new DoctrineObjectConstructor($managerRegistry, $fallbackConstructor);
    }
}
