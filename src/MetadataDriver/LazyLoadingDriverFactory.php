<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\MetadataDriver;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class LazyLoadingDriver
 *
 * @package Nnx\JmsSerializerModule\MetadataDriver
 */
class LazyLoadingDriverFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return LazyLoadingDriver
     * @throws \Nnx\JmsSerializerModule\MetadataDriver\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        if (!array_key_exists('realDriverId', $creationOptions)) {
            $errMsg = 'RealDriverId in LazyLoadingDriver not found';
            throw new Exception\RuntimeException($errMsg);
        }

        $realDriverId = $creationOptions['realDriverId'];

        if (!$serviceLocator instanceof ContainerInterface) {
            $errMsg = sprintf('Service  locator not implement %s', ContainerInterface::class);
            throw new Exception\RuntimeException($errMsg);
        }

        return new LazyLoadingDriver($serviceLocator, $realDriverId);
    }
}
