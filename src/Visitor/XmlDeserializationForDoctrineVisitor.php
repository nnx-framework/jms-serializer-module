<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\Visitor;


use JMS\Serializer\AbstractVisitor;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use Nnx\JmsSerializerModule\DataContainerBuilder\XmlBuilderInterface;
use SplStack;

/**
 * Class XmlDeserializationVisitor
 *
 * @package Nnx\JmsSerializerModule\Visitor
 */
class XmlDeserializationForDoctrineVisitor extends AbstractVisitor
{
    /**
     * Компонент для обхода иерархоических сущностей
     *
     * @var GraphNavigator
     */
    protected $navigator;

    /**
     * Сервис для подготовки данных из SimpleXMLElement
     *
     * @var XmlBuilderInterface
     */
    protected $dataContainerFromXmlBuilder;

    /**
     * Обрабатываемый объект
     *
     * @var mixed
     */
    protected $resultObject;

    /**
     * Тип первого визита (visitArray или массив)
     *
     * @var string
     */
    protected $firstVisitType;

    /**
     * XmlDeserializationForDoctrineVisitor constructor.
     *
     * @param PropertyNamingStrategyInterface $namingStrategy
     * @param XmlBuilderInterface             $dataContainerFromXmlBuilder
     */
    public function __construct(PropertyNamingStrategyInterface $namingStrategy, XmlBuilderInterface $dataContainerFromXmlBuilder)
    {
        $this->setDataContainerFromXmlBuilder($dataContainerFromXmlBuilder);
        parent::__construct($namingStrategy);
    }


    /**
     * @inheritdoc
     *
     * @param mixed $data
     *
     * @return mixed
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\RuntimeException
     */
    public function prepare($data)
    {
        return $this->getDataContainerFromXmlBuilder()->loadDataFromResource($data);
    }

    /**
     * Возвращает сервис для подготовки данных из SimpleXMLElement
     *
     * @return XmlBuilderInterface
     */
    public function getDataContainerFromXmlBuilder()
    {
        return $this->dataContainerFromXmlBuilder;
    }

    /**
     * Устанавливает сервис для подготовки данных из SimpleXMLElement
     *
     * @param XmlBuilderInterface $dataContainerFromXmlBuilder
     *
     * @return $this
     */
    public function setDataContainerFromXmlBuilder(XmlBuilderInterface $dataContainerFromXmlBuilder)
    {
        $this->dataContainerFromXmlBuilder = $dataContainerFromXmlBuilder;

        return $this;
    }




    public function visitNull($data, array $type, Context $context)
    {
        // TODO: Implement visitNull() method.
    }

    public function visitString($data, array $type, Context $context)
    {
        // TODO: Implement visitString() method.
    }

    public function visitBoolean($data, array $type, Context $context)
    {
        // TODO: Implement visitBoolean() method.
    }

    public function visitDouble($data, array $type, Context $context)
    {
        // TODO: Implement visitDouble() method.
    }

    public function visitInteger($data, array $type, Context $context)
    {
        // TODO: Implement visitInteger() method.
    }


    /**
     * Извлекает имя сущности
     *
     * @param array $type
     *
     * @return array
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\InvalidArgumentException
     */
    public function extractTypeDataForArray(array $type)
    {
        if (!array_key_exists('params', $type)) {
            $errMsg = 'Section "params" not found';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        if (!is_array($type['params'])) {
            $errMsg = 'Section "params" is not array';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        if (1 !== count($type['params'])) {
            $errMsg = 'Section "params" invalid';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        reset($type['params']);
        $params = current($type['params']);

        if (!array_key_exists('name', $params)) {
            $errMsg = 'Section "name" not found';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        return $params;
    }


    /**
     * @inheritdoc
     *
     * @param mixed   $data
     * @param array   $type
     * @param Context $context
     *
     * @return array
     * @throws \Nnx\JmsSerializerModule\Visitor\Exception\InvalidArgumentException
     */
    public function visitArray($data, array $type, Context $context)
    {
        
        $extractedType = $this->extractTypeDataForArray($type);
        return $this->getNavigator()->accept($data, $extractedType, $context);
    }

    /**
     * @inheritdoc
     *
     * @param ClassMetadata $metadata
     * @param mixed         $data
     * @param array         $type
     * @param Context       $context
     */
    public function startVisitingObject(ClassMetadata $metadata, $data, array $type, Context $context)
    {
        $this->resultObject = $data;
    }

    public function visitProperty(PropertyMetadata $metadata, $data, Context $context)
    {
        // TODO: Implement visitProperty() method.
    }

    /**
     * @inheritdoc
     *
     * @param ClassMetadata $metadata
     * @param mixed         $data
     * @param array         $type
     * @param Context       $context
     *
     * @return mixed
     */
    public function endVisitingObject(ClassMetadata $metadata, $data, array $type, Context $context)
    {
        return $this->resultObject;
    }

    /**
     * @inheritdoc
     *
     * @param GraphNavigator $navigator
     *
     * @return $this
     */
    public function setNavigator(GraphNavigator $navigator)
    {
        $this->navigator = $navigator;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return GraphNavigator
     */
    public function getNavigator()
    {
        return $this->navigator;
    }

    /**
     * @inheritdoc
     *
     * @return null
     */
    public function getResult()
    {

    }


}
