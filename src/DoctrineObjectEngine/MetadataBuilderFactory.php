<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MetadataBuilderFactory
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
class MetadataBuilderFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $metadataPrototype = new Metadata();
        return new MetadataBuilder($metadataPrototype);
    }
}
