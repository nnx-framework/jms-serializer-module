<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DoctrineObjectEngineFactory
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
class DoctrineObjectEngineFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var MetadataBuilderInterface $simpleFixtureMetadataBuilder */
        $simpleFixtureMetadataBuilder = $serviceLocator->get(MetadataBuilderInterface::class);

        /** @var ImportEngineInterface $simpleFixtureImportEngine */
        $simpleFixtureImportEngine = $serviceLocator->get(ImportEngineInterface::class);
        return new DoctrineObjectEngine($simpleFixtureMetadataBuilder, $simpleFixtureImportEngine);
    }
}
