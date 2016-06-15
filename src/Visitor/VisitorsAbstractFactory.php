<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Visitor;

use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\JmsSerializerModule\Options\ModuleOptions;
use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\VisitorInterface;

/**
 * Class VisitorsAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\Visitor
 */
class VisitorsAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool|void
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return 0 === strpos($requestedName, 'nnxJmsSerializer.visitors.');
    }

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return ObjectConstructorInterface
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\ObjectConstructor\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\JmsSerializerModule\Options\Exception\InvalidArgumentException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $visitorName = substr($requestedName, 26);

        /** @var  ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);

        $visitorConfig = $moduleOptions->getVisitor($visitorName);

        $name = $visitorConfig->getName();
        $options = $visitorConfig->getOptions();

        $visitor =  $serviceLocator->get(
            $name,
            $options
        );

        if (!$visitor instanceof VisitorInterface) {
            $errMsg = sprintf(
                'VisitorInterface of type %s is invalid; must implement %s',
                (is_object($visitor) ? get_class($visitor) : gettype($visitor)),
                VisitorInterface::class
            );
            throw new Exception\RuntimeException($errMsg);
        }

        return $visitor;
    }
}
