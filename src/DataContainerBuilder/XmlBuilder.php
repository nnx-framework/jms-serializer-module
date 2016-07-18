<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainerBuilder;

use Nnx\JmsSerializerModule\DataContainer;
use LibXMLError;
use SimpleXMLElement;

/**
 * Class ManagerRegistryFactory
 *
 * @package Nnx\JmsSerializerModule\Util
 */
class XmlBuilder implements XmlBuilderInterface
{
    /**
     * Имя корневого тега по умолчанию
     *
     * @var string
     */
    protected $defaultRootName = 'result';

    /**
     * Имя тега являющегося контейнером для сущности
     *
     * @var string
     */
    protected $xmlEntryName = 'entry';

    /**
     * Xpath запрос для получения вложенных сущностей
     *
     * @var string
     */
    protected $childEntryQuery;


    /**
     * Подготавливает нормализованный контейнер с данными на основе узла SimpleXMLElement
     *
     * @param string $resource
     *
     * @return DataContainer\DataContainerInterface
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\XmlBuilder\Exception\InvalidParserContextException
     * @throws \Nnx\JmsSerializerModule\DataContainer\Exception\InvalidArgumentException
     * @throws Exception\InvalidResourceException
     */
    public function loadDataFromResource($resource)
    {
        $xml = $this->buildXmlFromString($resource);

        $query = sprintf('/descendant-or-self::%s/%s', $this->getDefaultRootName(), $this->getXmlEntryName());
        $itemNodes = $xml->xpath($query);

        if (0 === count($itemNodes)) {
            $itemNodes = $xml->xpath('.');
        }


        $index = new DataContainer\Index();
        $dataContainer = new DataContainer\DataContainer($index);

        $context = new XmlBuilder\ParserContext();
        $context
            ->setIndex($index)
            ->setItemNodes($itemNodes)
            ->setDataContainer($dataContainer);

        $this->parseItem($context);

        return $dataContainer;
    }

    /**
     * На основе строки, подготавливает SimpleXMLElement
     *
     * @param $xmlString
     *
     * @return SimpleXMLElement
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\Exception\RuntimeException
     */
    protected function buildXmlFromString($xmlString)
    {

        $previous = libxml_use_internal_errors(true);
        $previousEntityLoaderState = libxml_disable_entity_loader(true);

        $xml = simplexml_load_string($xmlString);
        libxml_use_internal_errors($previous);
        libxml_disable_entity_loader($previousEntityLoaderState);

        $err = libxml_get_last_error();
        if ($err instanceof LibXMLError) {
            throw new Exception\RuntimeException($err->message);
        }

        return $xml;
    }
    /**
     * Обработка набора узлов xml документа, в которых описываются данные для сущности
     *
     * @param XmlBuilder\ParserContext $context
     *
     * @return DataContainer\DataContainerInterface
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\DataContainer\Exception\InvalidArgumentException
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\XmlBuilder\Exception\InvalidParserContextException
     *
     */
    protected function parseItem(XmlBuilder\ParserContext $context)
    {
        $context->validate();

        $itemNodes = $context->getItemNodes();
        $level = $context->getLevel();
        $parentEntity = $context->getParentEntity();
        $parentAssociationName = $context->getParentAssociation();
        $dataContainer = $context->getDataContainer();
        $index = $context->getIndex();


        foreach ($itemNodes as $itemNode) {
            $properties = $itemNode->xpath('./*');

            if (0 === count($properties)) {
                continue;
            }

            $entity = new DataContainer\Entity();
            $entity->setLevel($level);


            if (null !== $parentEntity) {
                $entity->setParentEntity($parentEntity);

                if (!$parentEntity->hasAssociation($parentAssociationName)) {
                    $association = new DataContainer\Association($index);
                    $association->setName($parentAssociationName);
                    $parentEntity->addAssociation($association);
                } else {
                    $association = $parentEntity->getAssociation($parentAssociationName);
                }

                $association->addEntity($entity);
            } else {
                $dataContainer->addEntity($entity);
            }

            $existingProperties = [];
            foreach ($properties as $property) {
                $propertyName = $property->getName();

                if (array_key_exists($propertyName, $existingProperties)) {
                    $errMsg = sprintf('Property %s already exists', $propertyName);
                    throw new Exception\RuntimeException($errMsg);
                }
                $existingProperties[$propertyName] = $propertyName;

                $childElements = $property->xpath('./*');

                if (count($childElements) > 0) {
                    $childEntryCollection = $property->xpath($this->getChildEntryQuery());
                    if (count($childEntryCollection) > 0) {
                        $childItems = $childEntryCollection;
                    } else {
                        $childItems = $property->xpath('.');
                    }

                    $childLevel = $level + 1;
                    $newContext = new XmlBuilder\ParserContext();
                    $newContext
                        ->setItemNodes($childItems)
                        ->setParentEntity($entity)
                        ->setLevel($childLevel)
                        ->setParentAssociation($propertyName)
                        ->setDataContainer($context->getDataContainer())
                        ->setIndex($context->getIndex());

                    $this->parseItem($newContext);
                } else {
                    $propertyValue = (string)$property;
                    $property = new DataContainer\Property();
                    $property
                        ->setName($propertyName)
                        ->setValue($propertyValue)
                    ;
                    $entity->addProperty($property);
                }
            }
        }
    }


    /**
     * Возвращает имя корневого тега по умолчанию
     *
     * @return string
     */
    public function getDefaultRootName()
    {
        return $this->defaultRootName;
    }

    /**
     * Устанавливает имя корневого тега по умолчанию
     *
     * @param string $defaultRootName
     *
     * @return $this
     */
    public function setDefaultRootName($defaultRootName)
    {
        $this->defaultRootName = $defaultRootName;

        return $this;
    }

    /**
     * Возвращает имя тега являющегося контейнером для сущности
     *
     * @return string
     */
    public function getXmlEntryName()
    {
        return $this->xmlEntryName;
    }

    /**
     * Устанавливает имя тега являющегося контейнером для сущности
     *
     * @param string $xmlEntryName
     *
     * @return $this
     */
    public function setXmlEntryName($xmlEntryName)
    {
        $this->xmlEntryName = $xmlEntryName;

        return $this;
    }

    /**
     * Возвращает  Xpath запрос для получения вложенных сущностей
     *
     * @return string
     */
    public function getChildEntryQuery()
    {
        if (null === $this->childEntryQuery) {
            $this->childEntryQuery = sprintf('./%s', $this->getXmlEntryName());
        }

        return $this->childEntryQuery;
    }

}
