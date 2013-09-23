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
    'mongo' => [
        'slave'  => [
            'slave1' => [
                'server' => 'localhost',
                'db'	 => 'nextcms',
                'port'	 => 27017,
            ],
        ],
        'master' => [
            'master1' => [
                'server' => 'localhost',
                'db'     => 'nextcms',
                'port'	 => 27017,
            ],
        ],
    ],
];
