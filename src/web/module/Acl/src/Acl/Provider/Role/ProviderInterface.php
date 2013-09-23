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

interface ProviderInterface
{
    /**
     * Return the array of roles
     *
     * @return \Acl\Provider\Role[]
     */
    public function getRoles();
}
