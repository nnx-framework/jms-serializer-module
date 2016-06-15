<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\NamingStrategy;

use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SerializedNameAnnotationStrategyFactory
 *
 * @package Nnx\JmsSerializerModule\NamingStrategy
 */
class SerializedNameAnnotationStrategyFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return SerializedNameAnnotationStrategy
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\NamingStrategy\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        if (!array_key_exists('delegate', $creationOptions)) {
            $errMsg = 'Delegate naming strategy not specified';
            throw new Exception\RuntimeException($errMsg);
        }
        $delegateName = $creationOptions['delegate'];

        /** @var PropertyNamingStrategyInterface $delegate */
        $delegate = $serviceLocator->get($delegateName);

        return new SerializedNameAnnotationStrategy($delegate);
    }
}
