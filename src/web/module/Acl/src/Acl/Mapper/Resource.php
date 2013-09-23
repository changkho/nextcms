<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Mapper;

use Mongo\Mapper\AbstractMapper;

class Resource extends AbstractMapper
{
    public function __construct()
    {
        parent::__construct('resource', 'Acl\Entity\Resource');
    }
}
