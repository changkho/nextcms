<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace User\Entity;

use Mongo\Entity;

class User extends Entity
{
    protected $properties = [
        'avatar'          => null,
        'bio'             => null,
        'birthday'        => null,
        'email'           => null,
        'first_name'      => '',
        'last_name'       => '',
        'gender'          => null,
        'locale'          => null,
        'profile_url'     => null,
        'role'            => 'guest',
        'user_name'       => null,
        'registered_date' => null,
        'status'          => 'activated',
        'last_logged_in'  => null,
        'website'         => null,
        'address'         => [
            'street1'  => null,
            'street2'  => null,
            'city'     => null,
            'state'    => null,
            'country'  => null,
            'zip_code' => null,
        ],

        // Taken from other authentication providers such as OpenId, OAuth, ...
        'auth'            => [],

        'secret_key'      => null,
    ];
}
