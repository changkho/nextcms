<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\View\Helper;

use Acl\Service\Authentication;
use Zend\View\Helper\AbstractHelper;

class Identity extends AbstractHelper
{
    public function __invoke()
    {
        return Authentication::getInstance()->hasIdentity() ? Authentication::getInstance()->getIdentity() : null;
    }
}
