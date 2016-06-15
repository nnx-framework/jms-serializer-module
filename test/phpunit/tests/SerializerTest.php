<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\PhpUnit\Test;

use Nnx\JmsSerializerModule\PhpUnit\TestData\TestPaths;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\JmsSerializerModule\Serializer\JmsSerializer;


/**
 * Class SerializerTest
 *
 * @package Nnx\JmsSerializerModule\PhpUnit\Test
 */
class SerializerTest extends AbstractHttpControllerTestCase
{
    /**
     *
     * @return void
     * @throws \Zend\Stdlib\Exception\LogicException
     */
    public function testGetDefaultSerializer()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToDefaultAppConfig()
        );

        /** @var ServiceLocatorInterface $serializerAdapterManager */
        $serializerAdapterManager = $this->getApplicationServiceLocator()->get('SerializerAdapterManager');

        $jmsSerializer = $serializerAdapterManager->get('nnxJmsSerializer.serializers.default');

        static::assertInstanceOf(JmsSerializer::class, $jmsSerializer);
    }
}
