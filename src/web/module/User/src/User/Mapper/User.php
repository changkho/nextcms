<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace User\Mapper;

use Mongo\Mapper\AbstractMapper;

class User extends AbstractMapper
{
    public function __construct()
    {
        parent::__construct('user', 'User\Entity\User');
    }
}
