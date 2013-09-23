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
    'service_manager' => [
        'factories'  => [
            'Session\Service\SaveHandler' => 'Session\Service\MongoDbHandlerFactory',
        ],
    ],
];
