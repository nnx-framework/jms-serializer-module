<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\NamingStrategy;

use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CamelCaseNamingStrategyFactory
 *
 * @package Nnx\JmsSerializerModule\NamingStrategy
 */
class CamelCaseNamingStrategyFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return CamelCaseNamingStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        $separator = '_';
        if (array_key_exists('separator', $creationOptions)) {
            $separator = (string)$creationOptions['separator'];
        }

        $lowerCase = true;
        if (array_key_exists('lowerCase', $creationOptions)) {
            $lowerCase = (boolean)$creationOptions['lowerCase'];
        }

        return new CamelCaseNamingStrategy($separator, $lowerCase);
    }
}
