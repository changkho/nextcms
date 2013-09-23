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

use MongoDB;

/**
 * The \MongoDB does not provide a method to get the client instance
 */
class Db extends MongoDB
{
    /**
     * @var \MongoClient
     */
    protected $client;

    public function __construct($mongo, $name)
    {
        parent::__construct($mongo, $name);
        $this->client = $mongo;
    }

    /**
     * Get the client instance
     *
     * @return \Mongo|\MongoClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
