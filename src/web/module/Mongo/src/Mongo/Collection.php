<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Mongo;

use MongoId;

class Collection implements DbAwareInterface
{
    /**
     * @var \Mongo\Db
     */
    protected $db;

    /**
     * The name of collection
     * @var string
     */
    protected $collection;

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function add($entity)
    {
        $this->db
             ->selectCollection($this->collection)
             ->insert($entity);
        return $entity;
    }

    public function count($criteria = [])
    {
        return $this->db
                    ->selectCollection($this->collection)
                    ->count($criteria);
    }

    public function delete($criteria = [])
    {
        $this->db
             ->selectCollection($this->collection)
             ->remove($criteria);
    }

    public function find($criteria = [], $start = 0, $count = 20, $sortBy = null)
    {
        $cursor = $this->db
                       ->selectCollection($this->collection)
                       ->find($criteria);
        if (is_numeric($start)) {
            $cursor->skip($start);
        }
        if (is_numeric($count)) {
            $cursor->limit($count);
        }
        if (is_array($sortBy)) {
            $cursor->sort($sortBy);
        }
        return $cursor;
    }

    public function findOne($criteria = [], $entityClass = null)
    {
        $row = $this->db
                    ->selectCollection($this->collection)
                    ->findOne($criteria);
        return (null == $entityClass) ? $row : new $entityClass($row);
    }

    public function update($entity)
    {
        $id = null;
        foreach (['id', '_id'] as $key) {
            if (isset($entity[$key])) {
                $id = (string) $entity[$key];
                unset($entity[$key]);
            }
        }

        $this->db
             ->selectCollection($this->collection)
             ->update(['_id' => new MongoId($id)], ['$set' => $entity], ['multiple' => true]);
    }
}
