<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainerBuilder;

use Nnx\JmsSerializerModule\DataContainer;
use Ramsey\Uuid\Uuid;
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
     * Ключем является хеш SimpleXMLElement, а значением закешированные данные
     *
     * @var array
     */
    protected $itemNodeUuidToCacheData = [];

    /**
     * Имя атрибута для того что бы связать SimpleXMLElement и контейнер с данными
     *
     * @var string
     */
    protected $defaultUuidAttribute = '____UUID____';

    /**
     * Определяет есть ли готовые данные для данного элемента
     *
     * @param SimpleXMLElement $resource
     *
     * @return boolean
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\Exception\RuntimeException
     */
    public function hasDataInCache(SimpleXMLElement $resource)
    {
        if ($this->hasUuidAttribute($resource)) {
            $uuid = $this->getUuidAttribute($resource);
            return array_key_exists($uuid, $this->itemNodeUuidToCacheData);
        }
        return false;
    }

    /**
     * Возвращает контейнер с данными из кеша
     *
     * @param SimpleXMLElement $resource
     *
     * @return DataContainer\DataContainerInterface
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\Exception\RuntimeException
     */
    public function getDataContainerFromCache(SimpleXMLElement $resource)
    {
        if (!$this->hasDataInCache($resource)) {
            $errMsg = 'Data container not found';
            throw new Exception\RuntimeException($errMsg);
        }

        $uuid = $this->getUuidAttribute($resource);
        return $this->itemNodeUuidToCacheData[$uuid]['dataContainer'];
    }

    /**
     * Возвращает контейнер с данными для сущности
     *
     * @param SimpleXMLElement $resource
     *
     * @return DataContainer\EntityInterface
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\Exception\RuntimeException
     */
    public function getEntityFromCache(SimpleXMLElement $resource)
    {
        if (!$this->hasDataInCache($resource)) {
            $errMsg = 'Entity not found';
            throw new Exception\RuntimeException($errMsg);
        }

        $uuid = $this->getUuidAttribute($resource);
        return $this->itemNodeUuidToCacheData[$uuid]['entity'];
    }

    /**
     * Подготавливает нормализованный контейнер с данными на основе узла SimpleXMLElement
     *
     * @param SimpleXMLElement $resource
     *
     * @return DataContainer\DataContainerInterface
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\Exception\RuntimeException
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\XmlBuilder\Exception\InvalidParserContextException
     * @throws \Nnx\JmsSerializerModule\DataContainer\Exception\InvalidArgumentException
     * @throws Exception\InvalidResourceException
     */
    public function loadDataFromResource(SimpleXMLElement $resource)
    {
        if ($this->hasUuidAttribute($resource)) {
            $uuid = $this->getUuidAttribute($resource);
            if (array_key_exists($uuid, $this->itemNodeUuidToCacheData)) {
                return $this->itemNodeUuidToCacheData[$uuid]['dataContainer'];
            }
        }

        $query = sprintf('/descendant-or-self::%s/%s', $this->getDefaultRootName(), $this->getXmlEntryName());
        $itemNodes = $resource->xpath($query);

        if (0 === count($itemNodes)) {
            $itemNodes = $resource->xpath('.');
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


            if (!$this->hasUuidAttribute($itemNode)) {
                $this->generateUuid($itemNode);
            }
            $currentUuid = $this->getUuidAttribute($itemNode);

            if (!array_key_exists($currentUuid, $this->itemNodeUuidToCacheData)) {
                $this->itemNodeUuidToCacheData[$currentUuid] = [
                    'entity' => $entity,
                    'dataContainer' => $dataContainer
                ];
            }


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
     * Определяет есть ли uuid атрибут у элемента
     *
     * @param SimpleXMLElement $itemNode
     *
     * @return bool
     */
    public function hasUuidAttribute(SimpleXMLElement $itemNode)
    {
        $uuidAttribute = $this->getDefaultUuidAttribute();
        $attributesItemNode = $itemNode->attributes();
        return isset($attributesItemNode[$uuidAttribute]);
    }


    /**
     * Определяет есть ли uuid атрибут у элемента
     *
     * @param SimpleXMLElement $itemNode
     *
     * @return string
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\Exception\RuntimeException
     */
    public function getUuidAttribute(SimpleXMLElement $itemNode)
    {
        if (!$this->hasUuidAttribute($itemNode)) {
            $errMsg = sprintf('Uuid attribute not found in %s', $itemNode->getName());
            throw new Exception\RuntimeException($errMsg);
        }

        $uuidAttribute = $this->getDefaultUuidAttribute();
        return (string)$itemNode[$uuidAttribute];
    }

    /**
     * Генерирует uuid атррибут
     *
     * @param SimpleXMLElement $itemNode
     *
     * @return SimpleXMLElement
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\Exception\RuntimeException
     */
    public function generateUuid(SimpleXMLElement $itemNode)
    {
        if ($this->hasUuidAttribute($itemNode)) {
            $errMsg = sprintf('Uuid attribute already exists %s', $itemNode->getName());
            throw new Exception\RuntimeException($errMsg);
        }

        $uuidAttribute = $this->getDefaultUuidAttribute();
        $uuid = Uuid::uuid4()->toString();
        $itemNode->addAttribute($uuidAttribute, $uuid);

        return $itemNode;
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

    /**
     * Возвращает имя атрибута для того что бы связать SimpleXMLElement и контейнер с данными
     *
     * @return string
     */
    public function getDefaultUuidAttribute()
    {
        return $this->defaultUuidAttribute;
    }

    /**
     * Устанавливает имя атрибута для того что бы связать SimpleXMLElement и контейнер с данными
     *
     * @param string $defaultUuidAttribute
     *
     * @return $this
     */
    public function setDefaultUuidAttribute($defaultUuidAttribute)
    {
        $this->defaultUuidAttribute = $defaultUuidAttribute;

        return $this;
    }
}
