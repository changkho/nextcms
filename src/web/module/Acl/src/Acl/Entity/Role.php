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

class Role extends Entity
{
    protected $properties = [
        'name'        => null,
        'description' => null,
        'parent'      => null,
    ];
}
