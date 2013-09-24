<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Dashboard extends AbstractHelper
{
    public function __invoke()
    {
        echo $this->getView()->render('user/_partial/dashboard.phtml');
    }
}
