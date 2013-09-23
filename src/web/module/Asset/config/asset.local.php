<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

// Sample settings
return [
    'asset' => [
        'cdn' => [
            'method'  => 'randomly',     // Can be 'random' or 'respective'
            'servers' => [
                'http://asset.nextcms.local',
            ],
        ],
    ],
];
