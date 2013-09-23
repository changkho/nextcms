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

class Resource extends Entity
{
    protected $properties = [
        'controller' => null,
        'parent'     => null,
    ];
}
