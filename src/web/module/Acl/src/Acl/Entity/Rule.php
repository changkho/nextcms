<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Entity;

use Mongo\Entity;

class Rule extends Entity
{
    protected $properties = [
        'resource'  => null,
        'obj_id'    => null,        // Can be user name or role name
        'obj_type'  => 'role',      // Can be user or role
        'privilege' => null,
        'allow'     => true,        // Can be true or false
    ];
}
