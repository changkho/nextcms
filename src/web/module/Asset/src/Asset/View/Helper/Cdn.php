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

use Zend\View\Helper\AbstractHelper;

class Cdn extends AbstractHelper
{
    use CdnTrait;

    /**
     * @param array $cdnOptions
     */
    public function __construct($cdnOptions = [])
    {
        $this->setCdnOptions($cdnOptions);
    }

    public function __invoke($src = null)
    {
        return (null == $src) ? $this : $this->appendCdn($src);
    }
}
