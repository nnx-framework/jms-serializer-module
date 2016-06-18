<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\MetadataReader;

use Doctrine\Common\Annotations\IndexedReader;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Nnx\JmsSerializerModule\Options\ModuleOptions;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Annotations\CachedReader;

/**
 * Class DefaultAnnotationReaderFactory
 *
 * @package Nnx\JmsSerializerModule\MetadataReader
 */
class DefaultAnnotationReaderFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     * @throws \Nnx\JmsSerializerModule\MetadataReader\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

//        AnnotationRegistry::registerLoader(function ($class) {
//            return (bool) class_exists($class);
//        });
        $reader = new AnnotationReader();
        $indexedReader = new IndexedReader($reader);

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager */
        $moduleOptionsPluginManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions  $moduleOptions */
        $moduleOptions = $moduleOptionsPluginManager->get(ModuleOptions::class);

        $annotationCacheName = $moduleOptions->getAnnotationCache();

        $annotationCache = $appServiceLocator->get($annotationCacheName);

        if (!$annotationCache instanceof Cache) {
            $errMsg = sprintf(
                'Annotation cache of type %s is invalid; must implement %s',
                (is_object($annotationCache) ? get_class($annotationCache) : gettype($annotationCache)),
                Cache::class
            );
            throw new Exception\RuntimeException($errMsg);
        }


        return new CachedReader($indexedReader, $annotationCache);
    }
}
