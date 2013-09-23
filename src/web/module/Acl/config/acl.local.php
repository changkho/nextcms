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
                '{ModuleName}\Controller\{ControllerName}',
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
                    ['member', '{ModuleName}\Controller\{ControllerName}', '{actionName}'],
                ],
            ],
        ],
        'guards' => [
            'Acl\Guard\Controller' => [
                [
                    'controller'      => '{ModuleName}\Controller\{ControllerName}',
                    'actions'         => ['{ActionName}'],
                    'request_methods' => ['{RequestMethod}'],      // Request method can be POST, GET, etc.
                    'callback'        => '{CallBackMethod}',
                ],
            ],
        ],
        'backend_layout' => [
            'default' => 'layout/member',
            'roles'   => [
                'member' => 'layout/member',
                'admin'  => 'layout/admin',
            ],
        ],
        'forbidden_template' => 'error/403',
        'signin_route'       => '...',          // The name of sign in route which the user will be redirected to if user has not logged in
    ],
];
