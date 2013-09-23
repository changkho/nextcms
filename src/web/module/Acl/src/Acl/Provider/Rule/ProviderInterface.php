<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Provider\Rule;

interface ProviderInterface
{
    /**
     * Return the array of rules
     *
     * @return \Acl\Entity\Rule[]
     */
    public function getRules();
}
