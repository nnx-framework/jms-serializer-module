<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Util;

use Zend\EventManager\EventManagerAwareTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ManagerRegistryFactory
 *
 * @package Nnx\JmsSerializerModule\Util
 */
class ManagerRegistryFactory implements FactoryInterface
{
    use EventManagerAwareTrait;

    /**
     * Идендификатор EventManager'a
     *
     * @var array
     */
    protected $eventIdentifier = [
        'DoctrineManagerRegistry'
    ];

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ManagerRegistry
     * @throws \Nnx\JmsSerializerModule\Util\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //@see \Nnx\Doctrine\Listener\ManagerRegistryListener
        $results = $this->getEventManager()->trigger('get.doctrineManagerRegistry', $this, [], function ($managerRegistry) {
            return $managerRegistry instanceof ManagerRegistry;
        });

        $managerRegistry = $results->last();

        if (!$managerRegistry instanceof ManagerRegistry) {
            $errMsg = 'ManagerRegistry not found';
            throw new Exception\RuntimeException($errMsg);
        }

        return $managerRegistry;
    }
}
