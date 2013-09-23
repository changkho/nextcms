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

interface MapperInterface
{
    /**
     * @param \Mongo\Entity|array $entity
     * @return \Mongo\Entity
     */
    public function create($entity);

    /**
     * Update given entity
     *
     * @param array $entity
     * @param array $options
     * @see http://php.net/manual/en/mongocollection.update.php
     * @return mixed
     */
    public function update($entity, $options = ['multiple' => true]);

    public function delete($criteria);

    public function find($criteria = [], $start = 0, $count = 20, $sortBy = null);

    public function count($criteria = []);

    public function findOne($criteria = []);
}
