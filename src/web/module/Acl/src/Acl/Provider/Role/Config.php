<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Provider\Role;

use Acl\Provider\Role;

class Config implements ProviderInterface
{
    /**
     * @var array
     */
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getRoles()
    {
        $roles = [];
        foreach ($this->options as $key => $value) {
            $name    = is_numeric($key) ? $value : $key;
            $parents = (is_array($value) && count($value) > 0) ? $value : null;

            $role    = new Role($name);
            $role->setParents($parents);
            $roles[] = $role;
        }
        return $roles;
    }
}
