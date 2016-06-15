<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\ModuleServiceManager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Config;
use Nnx\JmsSerializerModule\Module;


/**
 * Class JmsSerializerModuleServiceManagerFactory
 *
 * @package Nnx\JmsSerializerModule\ModuleServiceManager
 */
class JmsSerializerModuleServiceManagerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return JmsSerializerModuleServiceManager
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appConfig = $serviceLocator->get('config');
        $isModuleConfig =
            is_array($appConfig)
            && array_key_exists(Module::MODULE_SERVICE_MANAGER_CONFIG_KEY, $appConfig)
            && is_array($appConfig[Module::MODULE_SERVICE_MANAGER_CONFIG_KEY]);
        $moduleServiceManagerConfig = $isModuleConfig ? $appConfig[Module::MODULE_SERVICE_MANAGER_CONFIG_KEY] : [];

        $configuration = new Config($moduleServiceManagerConfig);
        $jmsSerializerModuleServiceManager = new JmsSerializerModuleServiceManager($configuration);
        $jmsSerializerModuleServiceManager->setServiceLocator($serviceLocator);
        return $jmsSerializerModuleServiceManager;
    }
}
