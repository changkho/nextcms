<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Provider;

use Zend\Permissions\Acl\Role\GenericRole;

class Role extends GenericRole
{
    /**
     * @var string|string[]|null
     */
    protected $parents = null;

    public function setParents($parents)
    {
        $this->parents = $parents;
    }

    public function getParents()
    {
        return $this->parents;
    }
}
