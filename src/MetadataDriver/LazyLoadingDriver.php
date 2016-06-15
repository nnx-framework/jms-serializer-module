<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\MetadataDriver;

use Interop\Container\ContainerInterface;
use Metadata\Driver\DriverInterface;
use ReflectionClass;

/**
 * Class LazyLoadingDriver
 *
 * @package Nnx\JmsSerializerModule\MetadataDriver
 */
class LazyLoadingDriver implements DriverInterface
{
    /**
     * Контейнер в катором распологаются драйвера
     *
     * @var ContainerInterface
     */
    protected $serviceLocator;

    /**
     * Идендификатор драйвера
     *
     * @var string
     */
    protected $realDriverId;

    /**
     * LazyLoadingDriver constructor.
     *
     * @param ContainerInterface $container
     * @param                    $realDriverId
     */
    public function __construct(ContainerInterface $container, $realDriverId)
    {
        $this->setServiceLocator($container);
        $this->setRealDriverId($realDriverId);
    }

    /**
     * Возвращает контейнер в катором распологаются драйвера
     *
     * @return ContainerInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Устанавливает контейнер в катором распологаются драйвера
     *
     * @param ContainerInterface $serviceLocator
     *
     * @return $this
     */
    public function setServiceLocator(ContainerInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Возвращает идендификатор драйвера
     *
     * @return string
     */
    public function getRealDriverId()
    {
        return $this->realDriverId;
    }

    /**
     * Устанавливает идендификатор драйвера
     *
     * @param string $realDriverId
     *
     * @return $this
     */
    public function setRealDriverId($realDriverId)
    {
        $this->realDriverId = $realDriverId;

        return $this;
    }

    /**
     * {@ineheritdoc}
     * @param ReflectionClass $class
     *
     * @return \Metadata\ClassMetadata
     * @throws \Nnx\JmsSerializerModule\MetadataDriver\Exception\RuntimeException
     * @throws \Interop\Container\Exception\NotFoundException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function loadMetadataForClass(ReflectionClass $class)
    {
        $container = $this->getServiceLocator();
        $id = $this->getRealDriverId();
        $metadataDriver = $container->get($id);

        if (!$metadataDriver instanceof DriverInterface) {
            $errMsg = sprintf('Metadata driver not implement %s', DriverInterface::class);
            throw new Exception\RuntimeException($errMsg);
        }

        return $metadataDriver->loadMetadataForClass($class);
    }
}
