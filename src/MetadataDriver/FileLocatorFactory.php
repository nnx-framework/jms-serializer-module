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
use Metadata\Driver\FileLocator;


/**
 * Class FileLocatorFactory
 *
 * @package Nnx\JmsSerializerModule\MetadataDriver
 */
class FileLocatorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return FileLocator
     * @throws \Nnx\JmsSerializerModule\MetadataDriver\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        if (!array_key_exists('directories', $creationOptions)) {
            $errMsg = 'Directories for FileLocator is not specified';
            throw new Exception\RuntimeException($errMsg);
        }
        $directories = $creationOptions['directories'];

        if (!is_array($directories)) {
            $errMsg = 'Directories for FileLocator is not array';
            throw new Exception\RuntimeException($errMsg);
        }

        return new FileLocator($directories);
    }
}
