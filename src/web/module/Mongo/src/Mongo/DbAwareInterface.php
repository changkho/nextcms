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

interface DbAwareInterface
{
    /**
     * Set the MongoDB instance
     *
     * @param \Mongo\Db $db The MongoDB instance
     */
	public function setDb($db);

    /**
     * Get the MongoDB instance
     *
     * @return \Mongo\Db
     */
    public function getDb();
}
