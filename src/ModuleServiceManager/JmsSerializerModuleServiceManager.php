<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\ModuleServiceManager;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;


/**
 * Class JmsSerializerModuleServiceManager
 *
 * @package Nnx\JmsSerializerModule\ModuleServiceManager
 */
class JmsSerializerModuleServiceManager extends AbstractPluginManager implements ContainerInterface
{
    /**
     * @inheritdoc
     *
     * @param mixed $plugin
     *
     * @return bool|void
     */
    public function validatePlugin($plugin)
    {
        return true;
    }
}
