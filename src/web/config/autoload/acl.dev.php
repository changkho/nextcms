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
    'acl' => [
        'resource_providers' => [
            'Acl\Provider\Resource\Config' => [
                'Hello\Controller\Dashboard',
                'User\Controller\Profile',
            ],
        ],
        'role_providers' => [
            'Acl\Provider\Role\Config' => [
                'guest'  => [],
                'member' => ['guest'],
                'root'   => [],
            ],
        ],
        'rule_providers' => [
            'Acl\Provider\Rule\Config' => [
                'allow' => [
                    ['root', null, null],

                    ['member', 'Hello\Controller\Dashboard', 'index'],

                    ['member', 'User\Controller\Profile', 'edit'],
                    ['member', 'User\Controller\Profile', 'password'],
                ],
            ],
        ],
        'guards' => [
            'Acl\Guard\Controller' => [],
        ],
        'backend_layout' => [
            'default' => 'layout/admin',
        ],
        'forbidden_template' => 'error/403',
        'signin_route'       => 'user\auth\signin',
    ],
];
