<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\ObjectConstructor\DoctrineObjectConstructor;

use Nnx\JmsSerializerModule\DataContainer\DataContainerInterface;
use Nnx\JmsSerializerModule\DataContainer\EntityInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DataFactory
 *
 * @package Nnx\JmsSerializerModule\ObjectConstructor\DoctrineObjectConstructor
 */
class DataFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Data
     * @throws \Nnx\JmsSerializerModule\ObjectConstructor\DoctrineObjectConstructor\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();
        if (!array_key_exists('dataContainer', $creationOptions)) {
            $errMsg = 'Data container not found in creation options';
            throw new Exception\RuntimeException($errMsg);
        }
        /** @var  DataContainerInterface $dataContainer */
        $dataContainer = $creationOptions['dataContainer'];

        if (!array_key_exists('entity', $creationOptions)) {
            $errMsg = 'Entity not found in creation options';
            throw new Exception\RuntimeException($errMsg);
        }
        /** @var  EntityInterface $entity */
        $entity = $creationOptions['entity'];

        return new Data($dataContainer, $entity);
    }
}
