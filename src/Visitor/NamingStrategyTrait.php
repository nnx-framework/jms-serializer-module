<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Visitor;

use Zend\ServiceManager\ServiceLocatorInterface;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;

/**
 * Class NamingStrategyTrait
 *
 * @package Nnx\JmsSerializerModule\Visitor
 */
trait NamingStrategyTrait
{
    /**
     * Настройки переданные при создание
     *
     * @return array
     */
    abstract public function getCreationOptions();


    /**
     * Возвращает стратегию для работы с именами свойств объектов
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return PropertyNamingStrategyInterface
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    protected function getNamingStrategyFromContainer(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();
        if (!array_key_exists('namingStrategy', $creationOptions)) {
            $errMsg = 'Naming strategy not specified';
            throw new Exception\RuntimeException($errMsg);
        }

        $namingStrategy = $creationOptions['namingStrategy'];

        return $serviceLocator->get($namingStrategy);
    }
}
