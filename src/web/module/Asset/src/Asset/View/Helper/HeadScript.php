<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Asset\View\Helper;

use Zend\View\Helper\HeadScript as BaseHeadScript;

class HeadScript extends BaseHeadScript
{
    use CdnTrait;

    /**
     * @param array $cdnOptions
     */
    public function __construct($cdnOptions = [])
    {
        $this->setCdnOptions($cdnOptions);
        parent::__construct();
    }

    public function append($value)
    {
        $value->attributes['src'] = $this->appendCdn($value->attributes['src']);
        parent::append($value);
    }

    public function prepend($value)
    {
        $value->attributes['src'] = $this->appendCdn($value->attributes['src']);
        parent::prepend($value);
    }

    public function set($value)
    {
        $value->attributes['src'] = $this->appendCdn($value->attributes['src']);
        parent::set($value);
    }

    public function offsetSet($index, $value)
    {
        $value->attributes['src'] = $this->appendCdn($value->attributes['src']);
        parent::offsetSet($index, $value);
    }
}
