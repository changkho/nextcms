<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

return [
    'session' => [
        'options' => [
            'use_cookies'      => true,
            'use_only_cookies' => true,
            'cookie_httponly'  => true,
            'name'             => '___Session___',
            'cookie_lifetime'  => 3600,
        ],
        'save_handler' => [
            'database'   => 'session',
            'collection' => 'session',
        ],
    ],
];
