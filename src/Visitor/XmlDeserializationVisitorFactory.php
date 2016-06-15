<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Visitor;

use JMS\Serializer\XmlDeserializationVisitor;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class XmlDeserializationVisitorFactory
 *
 * @package Nnx\JmsSerializerModule\Visitor
 */
class XmlDeserializationVisitorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait, NamingStrategyTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return XmlDeserializationVisitor
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        $namingStrategy = $this->getNamingStrategyFromContainer($serviceLocator);
        $xmlDeserializationVisitor = new XmlDeserializationVisitor($namingStrategy);

        if (array_key_exists('doctypeWhitelist', $creationOptions)) {
            $doctypeWhitelist = $creationOptions['doctypeWhitelist'];
            if (!is_array($doctypeWhitelist)) {
                $errMsg = 'Doctype whitelist not array';
                throw new Exception\RuntimeException($errMsg);
            }
            $xmlDeserializationVisitor->setDoctypeWhitelist($doctypeWhitelist);
        }

        return $xmlDeserializationVisitor;
    }
}
