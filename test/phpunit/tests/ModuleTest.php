<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\PhpUnit\Test;

use Nnx\JmsSerializerModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\JmsSerializerModule\Module;

/**
 * Class ModuleTest
 *
 * @package Nnx\JmsSerializerModule\PhpUnit\Test
 */
class ModuleTest extends AbstractHttpControllerTestCase
{
    /**
     *
     * @return void
     * @throws \Zend\Stdlib\Exception\LogicException
     */
    public function testLoadModule()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToDefaultAppConfig()
        );

        $this->assertModulesLoaded([Module::MODULE_NAME]);
    }
}
