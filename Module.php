<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule;

use Nnx\ModuleOptions\ModuleConfigKeyProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\ModuleOptions\Module as ModuleOptions;

/**
 * Class Module
 *
 * @package Nnx\JmsSerializerModule
 */
class Module implements
    BootstrapListenerInterface,
    AutoloaderProviderInterface,
    InitProviderInterface,
    ConfigProviderInterface,
    ModuleConfigKeyProviderInterface,
    DependencyIndicatorInterface
{
    /**
     * Имя секции в конфиги приложения отвечающей за настройки модуля
     *
     * @var string
     */
    const CONFIG_KEY = 'nnx_jms_serializer';

    /**
     * Имя секции в конфиги приложения отвечающей за настройки сервис менеджера для данного модуля
     *
     * @var string
     */
    const MODULE_SERVICE_MANAGER_CONFIG_KEY = 'nnx_jms_serializer_service';


    /**
     * Имя модуля
     *
     * @var string
     */
    const MODULE_NAME = __NAMESPACE__;


    /**
     * @inheritDoc
     */
    public function getModuleDependencies()
    {
        return [
            ModuleOptions::MODULE_NAME
        ];
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getModuleConfigKey()
    {
        return static::CONFIG_KEY;
    }


    /**
     * @param ModuleManagerInterface $manager
     *
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function init(ModuleManagerInterface $manager)
    {
        if (!$manager instanceof ModuleManager) {
            $errMsg = sprintf('Module manager not implement %s', ModuleManager::class);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        /** @var ServiceLocatorInterface $sm */
        $sm = $manager->getEvent()->getParam('ServiceManager');

        if (!$sm instanceof ServiceLocatorInterface) {
            $errMsg = sprintf('Service locator not implement %s', ServiceLocatorInterface::class);
            throw new Exception\InvalidArgumentException($errMsg);
        }
        /** @var ServiceListenerInterface $serviceListener */
        $serviceListener = $sm->get('ServiceListener');
        if (!$serviceListener instanceof ServiceListenerInterface) {
            $errMsg = sprintf('ServiceListener not implement %s', ServiceListenerInterface::class);
            throw new Exception\InvalidArgumentException($errMsg);
        }


    }

    /**
     * @inheritdoc
     *
     * @param EventInterface $e
     *
     * @return array|void
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

    }


    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/',
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getConfig()
    {
        return array_merge_recursive(
            include __DIR__ . '/config/module.config.php',
            include __DIR__ . '/config/serviceManager.config.php',
            include __DIR__ . '/config/serializer.config.php',
            include __DIR__ . '/config/moduleServiceManager.config.php'
        );
    }

} 