<?php
/**
 * @link    https://github.com/nnx-framework/jms-serializer-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\JmsSerializerModule\DataContainer;

use Doctrine\Common\Inflector\Inflector;

/**
 * Class Entity
 *
 * @package Nnx\JmsSerializerModule\DataContainer
 */
class Entity implements EntityInterface
{
    /**
     * Внутренний идендификатор контейнера с данынми для фикстуры
     *
     * @var integer
     */
    protected $id;

    /**
     * Поле используемое для генерации id
     *
     * @var int
     */
    protected static $idGenerator = 0;

    /**
     * Уровень на котором расположенна сущность
     *
     * @var integer
     */
    protected $level;

    /**
     * Свойства сущности
     *
     * @var Property[]
     */
    protected $properties = [];

    /**
     * Связи с другими сущностями
     *
     * @var Association[]
     */
    protected $associations = [];

    /**
     * Родительская сущность
     *
     * @var EntityInterface|null
     */
    protected $parentEntity;

    /**
     * Возвращает уровень, на котором расположена сущность
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Устанавливает уровень на котором расположена сущность
     *
     * @param int $level
     *
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Возвращает родительскую сущность
     *
     * @return EntityInterface|null
     */
    public function getParentEntity()
    {
        return $this->parentEntity;
    }

    /**
     * Устанавливает родительскую сущность
     *
     * @param EntityInterface $parentEntity
     *
     * @return $this
     */
    public function setParentEntity(EntityInterface $parentEntity = null)
    {
        $this->parentEntity = $parentEntity;

        return $this;
    }

    /**
     * Добавляет связь на вложенную сущность
     *
     * @param Association $association
     *
     * @return $this
     */
    public function addAssociation(Association $association)
    {
        $this->associations[$association->getName()] = $association;

        return $this;
    }

    /**
     * Возвращает связи
     *
     * @return Association[]
     */
    public function getAssociations()
    {
        return $this->associations;
    }

    /**
     * Добавляет поле
     *
     * @param Property $property
     *
     * @return $this
     */
    public function addProperty(Property $property)
    {
        $normalizeName = Inflector::camelize($property->getName());

        $this->properties[$normalizeName] = $property;

        return $this;
    }

    /**
     * Возвращает набор полей сущности
     *
     * @return Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Определяет, есть ли связь, с заданным именем
     *
     * @param $name
     *
     * @return bool
     */
    public function hasAssociation($name)
    {
        return array_key_exists($name, $this->associations);
    }


    /**
     * Определяет, есть ли связь, с заданным именем
     *
     * @param $name
     *
     * @return Association
     * @throws \Nnx\JmsSerializerModule\DataContainer\Exception\InvalidArgumentException
     */
    public function getAssociation($name)
    {
        if (!array_key_exists($name, $this->associations)) {
            $errMsg = sprintf('Association %s not found', $name);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        return $this->associations[$name];
    }

    /**
     * Возвращает внутренний идендификатор контейнера с данынми для фикстуры
     *
     * @return int
     */
    public function getId()
    {
        if (null === $this->id) {
            static::$idGenerator += 1;
            $this->id = static::$idGenerator;
        }
        return $this->id;
    }
}
