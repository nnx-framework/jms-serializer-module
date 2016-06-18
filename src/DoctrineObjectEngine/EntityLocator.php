<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DoctrineObjectEngine;

use Interop\Container\ContainerInterface;
use ReflectionClass;

/**
 * Class EntityLocator
 *
 * @package Nnx\JmsSerializerModule\DoctrineObjectEngine
 */
class EntityLocator implements ContainerInterface
{
    /**
     * @inheritDoc
     */
    public function get($id)
    {
        $r = new ReflectionClass($id);

        return $r->newInstance();
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return class_exists($id);
    }
}
