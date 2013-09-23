<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Service;

use Zend\Authentication\AuthenticationService;

class Authentication
{
    /**
     * @var \Zend\Authentication\AuthenticationService
     */
    protected static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new AuthenticationService();
        }
        return self::$instance;
    }
}
