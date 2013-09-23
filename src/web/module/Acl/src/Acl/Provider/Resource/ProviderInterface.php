<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Provider\Resource;

interface ProviderInterface
{
    /**
     * Return the array of resources
     *
     * @return \Zend\Permissions\Acl\Resource\ResourceInterface[]
     */
    public function getResources();
}
