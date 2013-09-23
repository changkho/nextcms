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

use Zend\Permissions\Acl\Resource\GenericResource;

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

    public function getResources()
    {
        $resources = [];
        foreach ($this->options as $k => $v) {
            $resources[] = new GenericResource($v);
        }

        return $resources;
    }
}
