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

use Zend\View\Helper\HeadLink as BaseHeadLink;

class HeadLink extends BaseHeadLink
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
        $value->href = $this->appendCdn($value->href);
        parent::append($value);
    }

    public function prepend($value)
    {
        $value->href = $this->appendCdn($value->href);
        parent::prepend($value);
    }

    public function set($value)
    {
        $value->href = $this->appendCdn($value->href);
        parent::set($value);
    }

    public function offsetSet($index, $value)
    {
        $value->href = $this->appendCdn($value->href);
        parent::offsetSet($index, $value);
    }
}
