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
    'modules' => [
        'Acl',
        'Asset',
        'Hello',
        'Helper',
        'I18n',
        'Mongo',
        'Session',
        'User',
    ],

    'module_listener_options' => [
        'config_glob_paths' => [
            'config/autoload/{*}.' . APP_ENV . '.php',
        ],
        'module_paths' => [
            './module',
        ],
    ],
];
