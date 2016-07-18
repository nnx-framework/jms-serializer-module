<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\PhpUnit\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\SchemaTool;
use Nnx\JmsSerializerModule\PhpUnit\TestData\TestPaths;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\JmsSerializerModule\Serializer\JmsSerializer;
use Nnx\JmsSerializerModule\PhpUnit\TestData\DefaultApp\TestModule1\Entity;


/**
 * Class SerializerTest
 *
 * @package Nnx\JmsSerializerModule\PhpUnit\Test
 */
class SerializerTest extends AbstractHttpControllerTestCase
{

    /**
     * Установка окружения
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Doctrine\ORM\Tools\ToolsException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     */
    public function setUp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToDefaultAppConfig()
        );

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getApplication()->getServiceManager()->get('doctrine.entitymanager.test');

        $tool = new SchemaTool($em);
        $tool->dropDatabase();

        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $tool->createSchema($metadata);

        parent::setUp();
    }

    /**
     * Проверка конфигурации по умолчанию
     *
     * @return void
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
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

    /**
     * Проверка сериализации
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSerialize()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getApplication()->getServiceManager()->get('doctrine.entitymanager.test');

        $address1 = new Entity\Address();
        $address1->setCity('Nigniy Novgorod')->setStreet('Poltavskay');


        $address2 = new Entity\Address();
        $address2->setCity('Moscow')->setStreet('Arbat');


        $author1 = new Entity\Author();
        $author1->setAddress($address1)->setName('Ivan')->setFamilyName('Ivanov');


        $author2 = new Entity\Author();
        $author2->setAddress($address2)->setName('Petr')->setFamilyName('Petrov');


        $author3 = new Entity\Author();
        $author3->setAddress($address2)->setName('Vasiliy')->setFamilyName('Vasiliev');


        $book1 = new Entity\Book();
        $book1->addAuthor($author1)->addAuthor($author2);
        $author1->addBook($book1);
        $author2->addBook($book1);
        $book1->setDateOfPublication(new \DateTime('2016-06-17T08:37:01+0300'))->setTitle('Test Book')->setIsbn('123456789');


        $book2 = new Entity\Book();
        $book2->addAuthor($author3);
        $author3->addBook($book2);
        $book2->setDateOfPublication(new \DateTime('2016-06-17T08:37:01+0300'))->setTitle('Test Book - 2')->setIsbn('987654321');


        $em->persist($address1);
        $em->persist($address2);
        $em->persist($author1);
        $em->persist($author2);
        $em->persist($author3);
        $em->persist($book1);
        $em->persist($book2);

        $em->flush();

        $books = (array)$em->getRepository(Entity\Book::class)->findAll();


        /** @var ServiceLocatorInterface $serializerAdapterManager */
        $serializerAdapterManager = $this->getApplicationServiceLocator()->get('SerializerAdapterManager');

        /** @var JmsSerializer $jmsSerializer */
        $jmsSerializer = $serializerAdapterManager->get('nnxJmsSerializer.serializers.default');

        $data = $jmsSerializer->serialize($books, 'xml');

        static::assertXmlStringEqualsXmlFile(TestPaths::getPathToFixtureXmlDir() . 'books_entity.xml', $data);

        file_put_contents(TestPaths::getPathToFixtureXmlDir() . 'book_entity.xml', $jmsSerializer->serialize($book1, 'xml'));
    }


    /**
     * Проверка десериализации данных и получение \Doctrine\Common\Collections\ArrayCollection
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testDeserializeXmlToArrayCollection()
    {

        /** @var ServiceLocatorInterface $serializerAdapterManager */
        $serializerAdapterManager = $this->getApplicationServiceLocator()->get('SerializerAdapterManager');

        /** @var JmsSerializer $jmsSerializer */
        $jmsSerializer = $serializerAdapterManager->get('nnxJmsSerializer.serializers.default');

        $xmlData = file_get_contents(TestPaths::getPathToFixtureXmlDir() . 'books_entity.xml');
        $result = $jmsSerializer->unserialize(
            $xmlData,
            'ArrayCollection<Nnx\JmsSerializerModule\PhpUnit\TestData\DefaultApp\TestModule1\Entity\Book>',
            'xmlForDoctrine'
        );

        static::assertInstanceOf(ArrayCollection::class, $result);
    }


    /**
     * Проверка десериализации данных и получение массива
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testDeserializeXmlToArray()
    {

        /** @var ServiceLocatorInterface $serializerAdapterManager */
        $serializerAdapterManager = $this->getApplicationServiceLocator()->get('SerializerAdapterManager');

        /** @var JmsSerializer $jmsSerializer */
        $jmsSerializer = $serializerAdapterManager->get('nnxJmsSerializer.serializers.default');

        $xmlData = file_get_contents(TestPaths::getPathToFixtureXmlDir() . 'books_entity.xml');
        $result = $jmsSerializer->unserialize(
            $xmlData,
            'array<Nnx\JmsSerializerModule\PhpUnit\TestData\DefaultApp\TestModule1\Entity\Book>',
            'xmlForDoctrine'
        );

        static::assertInternalType('array', $result);
    }

    /**
     * Проверка десериализации данных и объекта
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testDeserializeXmlToObject()
    {

        /** @var ServiceLocatorInterface $serializerAdapterManager */
        $serializerAdapterManager = $this->getApplicationServiceLocator()->get('SerializerAdapterManager');

        /** @var JmsSerializer $jmsSerializer */
        $jmsSerializer = $serializerAdapterManager->get('nnxJmsSerializer.serializers.default');

        $xmlData = file_get_contents(TestPaths::getPathToFixtureXmlDir() . 'book_entity.xml');
        $result = $jmsSerializer->unserialize(
            $xmlData,
            'Nnx\JmsSerializerModule\PhpUnit\TestData\DefaultApp\TestModule1\Entity\Book',
            'xmlForDoctrine'
        );

        static::assertInternalType('array', $result);
    }



    /**
     * Проверка десериализации данных и получение массива
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testDeserializeJsonToArray()
    {

        /** @var ServiceLocatorInterface $serializerAdapterManager */
        $serializerAdapterManager = $this->getApplicationServiceLocator()->get('SerializerAdapterManager');

        /** @var JmsSerializer $jmsSerializer */
        $jmsSerializer = $serializerAdapterManager->get('nnxJmsSerializer.serializers.default');

        $xmlData = file_get_contents(TestPaths::getPathToFixtureXmlDir() . 'books_entity.json');
        $result = $jmsSerializer->unserialize(
            $xmlData,
            'array<Nnx\JmsSerializerModule\PhpUnit\TestData\DefaultApp\TestModule1\Entity\Book>',
            'json'
        );

        static::assertInternalType('array', $result);
    }
}
