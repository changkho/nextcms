<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Mongo\Mapper;

use Mongo\Entity;
use MongoId;

class AbstractMapper implements MapperInterface
{
    /** @var \Mongo\Db */
    protected $db;

    /**
     * The collection instance
     * @var \MongoCollection
     */
    protected $collection;

    /**
     * The collection name
     * @var string
     */
    protected $collectionName;

    /**
     * Class of the entity
     * @var string
     */
    protected $entityClass;

    /**
     * @param string $collectionName
     * @param string $entityClass
     */
    public function __construct($collectionName, $entityClass)
    {
        $this->collectionName = $collectionName;
        $this->entityClass    = $entityClass;
    }

    /**
     * Set the DB instance
     *
     * @param \Mongo\Db $db
     * @return $this
     */
    public function setDb($db)
    {
        $this->db         = $db;
        $this->collection = $db->selectCollection($this->collectionName);
        return $this;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function create($entity)
    {
        $properties = ($entity instanceof Entity) ? $entity->getProperties() : $entity;
        $this->collection->insert($properties);
        return new $this->entityClass($properties);
    }

    public function update($entity, $options = ['multiple' => true])
    {
        $id = null;
        foreach (['id', '_id'] as $key) {
            if (isset($entity[$key])) {
                $id = (string) $entity[$key];
                unset($entity[$key]);
            }
        }

        $this->collection->update(['_id' => new MongoId($id)], ['$set' => $entity], $options);
    }

    public function delete($criteria)
    {
        $this->collection->remove($criteria);
    }

    public function find($criteria = [], $start = 0, $count = 20, $sortBy = null)
    {
        $cursor = $this->collection->find($criteria);
        if (is_numeric($start)) {
            $cursor->skip($start);
        }
        if (is_numeric($count)) {
            $cursor->limit($count);
        }
        if (is_array($sortBy)) {
            $cursor->sort($sortBy);
        }

        if ($cursor == null || $cursor->count(true) == 0) {
            return null;
        }
        if ($count == 1) {
            return new $this->entityClass($cursor->getNext());
        } else {
            $entities = [];
            foreach ($cursor as $r) {
                $entities[] = new $this->entityClass($r);
            }
            return $entities;
        }
    }

    public function count($criteria = [])
    {
        return $this->collection->count($criteria);
    }

    public function findOne($criteria = [])
    {
        $row = $this->collection->findOne($criteria);
        return (null == $row) ? null : new $this->entityClass($row);
    }
}
