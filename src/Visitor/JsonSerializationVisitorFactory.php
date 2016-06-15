<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Visitor;

use JMS\Serializer\JsonSerializationVisitor;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Metadata\Driver\FileLocator;

/**
 * Class FileLocatorFactory
 *
 * @package Nnx\JmsSerializerModule\Visitor
 */
class JsonSerializationVisitorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait, NamingStrategyTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return FileLocator
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\MetadataDriver\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $namingStrategy = $this->getNamingStrategyFromContainer($serviceLocator);
        $visitor = new JsonSerializationVisitor($namingStrategy);

        $creationOptions = $this->getCreationOptions();
        if (array_key_exists('jsonOptions', $creationOptions)) {
            $jsonOptions = $creationOptions['jsonOptions'];
            if (!is_array($jsonOptions)) {
                $errMsg = 'Json options is not array';
                throw new Exception\RuntimeException($errMsg);
            }
            if (array_key_exists('options', $jsonOptions)) {
                $visitor->setOptions($jsonOptions['options']);
            }
        }

        return $visitor;
    }
}
