<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainerBuilder\XmlBuilder;

use Nnx\JmsSerializerModule\DataContainer\EntityInterface;
use SimpleXMLElement;
use Nnx\JmsSerializerModule\DataContainer\DataContainerInterface;
use Nnx\JmsSerializerModule\DataContainer\Index;

/**
 * Class ParserContext
 *
 * @package Nnx\JmsSerializerModule\DataContainerBuilder\XmlBuilder
 */
class ParserContext
{
    /**
     * @var SimpleXMLElement[]
     */
    protected $itemNodes;

    /**
     * Устанавливает связь, с контейнером в котором содержаться данные для родительской сущности
     *
     * @var EntityInterface
     */
    protected $parentEntity;

    /**
     * Уровень вложенности сущности в xml дереве, на котором находится обрабатываемый узел
     *
     * @var int
     */
    protected $level = 0;

    /**
     * Имя связи, которая связывает контейнер с  данные для сущности, с контейнером данных, родительской сущности
     *
     * @var string|null
     */
    protected $parentAssociation;

    /**
     * Контейнер с результирующими данными
     *
     * @var DataContainerInterface
     */
    protected $dataContainer;

    /**
     * Устанавливает индекс
     *
     * @var Index
     */
    protected $index;

    /**
     * Набор нод для парсинга
     *
     * @return SimpleXMLElement[]
     */
    public function getItemNodes()
    {
        return $this->itemNodes;
    }

    /**
     * Устанавливает набор нод для парсинга
     *
     * @param array $itemNodes
     *
     * @return $this
     */
    public function setItemNodes(array $itemNodes)
    {
        $this->itemNodes = $itemNodes;

        return $this;
    }

    /**
     * Возвращает связь, с контейнером в котором содержаться данные для родительской сущности
     *
     * @return EntityInterface
     */
    public function getParentEntity()
    {
        return $this->parentEntity;
    }

    /**
     * Устанавливает связь, с контейнером в котором содержаться данные для родительской сущности
     *
     * @param EntityInterface $parentEntity
     *
     * @return $this
     */
    public function setParentEntity(EntityInterface $parentEntity)
    {
        $this->parentEntity = $parentEntity;

        return $this;
    }

    /**
     * Возвращает уровень вложенности сущности в xml дереве, на котором находится обрабатываемый узел
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Устанавливает уровень вложенности сущности в xml дереве, на котором находится обрабатываемый узел
     *
     * @param int $level
     *
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = (integer)$level;

        return $this;
    }

    /**
     * Возвращает имя связи, которая связывает контейнер с  данные для сущности, с контейнером данных, родительской сущности
     *
     * @return null|string
     */
    public function getParentAssociation()
    {
        return $this->parentAssociation;
    }

    /**
     * Устанавливает имя связи, которая связывает контейнер с  данные для сущности, с контейнером данных, родительской сущности
     *
     * @param null|string $parentAssociation
     *
     * @return $this
     */
    public function setParentAssociation($parentAssociation)
    {
        $this->parentAssociation = $parentAssociation;

        return $this;
    }

    /**
     * Проверка корректного состояния контекста
     *
     * @throws \Nnx\JmsSerializerModule\DataContainerBuilder\XmlBuilder\Exception\InvalidParserContextException
     */
    public function validate()
    {
        $parentAssociation = $this->getParentAssociation();
        $parentEntity = $this->getParentEntity();

        if (null !== $parentAssociation && null === $parentEntity) {
            $errMsg = 'Parent entity not specified';
            throw new Exception\InvalidParserContextException($errMsg);
        }

        if (null === $parentAssociation && null !== $parentEntity) {
            $errMsg = 'Parent association not specified';
            throw new Exception\InvalidParserContextException($errMsg);
        }


        if (null === $this->getDataContainer()) {
            $errMsg = 'Data container not specified';
            throw new Exception\InvalidParserContextException($errMsg);
        }

        if (null === $this->getIndex()) {
            $errMsg = 'Index storage not specified';
            throw new Exception\InvalidParserContextException($errMsg);
        }
    }

    /**
     * Возвращает контейнер с результирующими данными
     *
     * @return DataContainerInterface
     */
    public function getDataContainer()
    {
        return $this->dataContainer;
    }

    /**
     * Устанавливает контейнер с результирующими данными
     *
     * @param DataContainerInterface $dataContainer
     *
     * @return $this
     */
    public function setDataContainer(DataContainerInterface $dataContainer)
    {
        $this->dataContainer = $dataContainer;

        return $this;
    }

    /**
     * Возвращает хранилище индексов
     *
     * @return Index
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Устанавливает хранилище индексов
     *
     * @param Index $index
     *
     * @return $this
     */
    public function setIndex(Index $index)
    {
        $this->index = $index;

        return $this;
    }
}
