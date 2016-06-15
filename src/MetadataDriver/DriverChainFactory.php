<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\MetadataDriver;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Metadata\Driver\DriverChain;
use Metadata\Driver\DriverInterface;

/**
 * Class DriverChainFactory
 *
 * @package Nnx\JmsSerializerModule\MetadataDriver
 */
class DriverChainFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return LazyLoadingDriver
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\MetadataDriver\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        $targetDrivers = [];
        if (array_key_exists('drivers', $creationOptions)) {
            $drivers = $creationOptions['drivers'];

            if (!is_array($drivers)) {
                $errMsg = 'Drivers config is not array';
                throw new Exception\RuntimeException($errMsg);
            }

            foreach ($drivers as $driver) {
                $targetDriver = $driver;
                if (is_string($driver)) {
                    $targetDriver = $serviceLocator->get($driver);
                }

                if (!$targetDriver instanceof DriverInterface) {
                    $errMsg = sprintf(
                        'Driver of type %s is invalid; must implement %s',
                        (is_object($targetDriver) ? get_class($targetDriver) : gettype($targetDriver)),
                        DriverInterface::class
                    );
                    throw new Exception\RuntimeException($errMsg);
                }

                $targetDrivers[] = $targetDriver;
            }
        }

        return new DriverChain($targetDrivers);
    }
}
