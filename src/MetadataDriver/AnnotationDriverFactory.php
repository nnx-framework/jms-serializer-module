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
use JMS\Serializer\Metadata\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\Reader;


/**
 * Class AnnotationDriverFactory
 *
 * @package Nnx\JmsSerializerModule\MetadataDriver
 */
class AnnotationDriverFactory implements FactoryInterface, MutableCreationOptionsInterface
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

        if (!array_key_exists('reader', $creationOptions)) {
            $errMsg = 'Annotation reader not specified';
            throw new Exception\RuntimeException($errMsg);
        }

        $reader = $serviceLocator->get($creationOptions['reader']);

        if (!$reader instanceof Reader) {
            $errMsg = sprintf('Annotation reader not implement %s', Reader::class);
            throw new Exception\RuntimeException($errMsg);
        }

        return new AnnotationDriver($reader);
    }
}
