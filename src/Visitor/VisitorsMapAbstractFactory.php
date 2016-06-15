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
use PhpCollection\Map;
use ReflectionClass;
use JMS\Serializer\VisitorInterface;

/**
 * Class VisitorsMapAbstractFactory
 *
 * @package Nnx\JmsSerializerModule\Visitor
 */
class VisitorsMapAbstractFactory implements AbstractFactoryInterface
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
        return 0 === strpos($requestedName, 'nnxJmsSerializer.serializationVisitors.') || 0 === strpos($requestedName, 'nnxJmsSerializer.deserializationVisitors.default');
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

        $name = null;

        $isSerializationVisitors = 0 === strpos($requestedName, 'nnxJmsSerializer.serializationVisitors.');
        $isDeserializationVisitors = 0 === strpos($requestedName, 'nnxJmsSerializer.deserializationVisitors.default');

        if ($isSerializationVisitors) {
            $name = substr($requestedName, 39);
        } elseif ($isDeserializationVisitors) {
            $name = substr($requestedName, 41);
        }

        /** @var  ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);


        $list = [];

        if ($isSerializationVisitors) {
            $list = $moduleOptions->getSerializationVisitor($name);
        } elseif ($isDeserializationVisitors) {
            $list = $moduleOptions->getDeserializationVisitor($name);
        }

        $map = new Map();


        foreach ($list as $format => $visitorName) {
            $visitor = null;
            if (is_string($visitorName)) {
                if ($serviceLocator->has($visitorName)) {
                    $visitor = $serviceLocator->get($visitorName);
                } elseif (class_exists($visitorName)) {
                    $r = new ReflectionClass($visitorName);
                    $visitor = $r->newInstance();
                }
            }

            if (!$visitor instanceof VisitorInterface) {
                $errMsg = sprintf(
                    'Visitor of type %s is invalid; must implement %s',
                    (is_object($visitor) ? get_class($visitor) : gettype($visitor)),
                    VisitorInterface::class
                );
                throw new Exception\RuntimeException($errMsg);
            }


            $map->set($format, $visitor);
        }


        return $map;
    }
}
