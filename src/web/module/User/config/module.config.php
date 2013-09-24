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
    'controllers' => [
        'invokables' => [
            'User\Controller\Auth'    => 'User\Controller\AuthController',
            'User\Controller\Profile' => 'User\Controller\ProfileController',
        ],
    ],

    'router' => [
        'routes' => [
            // Auth
            'user\auth\check' => [
                'type'	  => 'literal',
                'options' => [
                    'route'    => '/user/auth/check',
                    'defaults' => [
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'check',
                    ],
                ],
            ],
            'user\auth\signin' => [
                'type'	  => 'literal',
                'options' => [
                    'route'    => '/user/signin',
                    'defaults' => [
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'signin',
                    ],
                ],
            ],
            'user\auth\signout' => [
                'type'	  => 'literal',
                'options' => [
                    'route'    => '/user/signout',
                    'defaults' => [
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'signout',
                    ],
                ],
            ],
            'user\auth\signup' => [
                'type'	  => 'literal',
                'options' => [
                    'route'    => '/user/signup',
                    'defaults' => [
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'signup',
                    ],
                ],
            ],

            // Profile
            'user\profile\edit' => [
                'type'	  => 'literal',
                'options' => [
                    'route'    => '/user/profile/edit',
                    'defaults' => [
                        'controller' => 'User\Controller\Profile',
                        'action'     => 'edit',
                        'backend'    => true,
                    ],
                ],
            ],
            'user\profile\password' => [
                'type'	  => 'literal',
                'options' => [
                    'route'    => '/user/profile/password',
                    'defaults' => [
                        'controller' => 'User\Controller\Profile',
                        'action'     => 'password',
                        'backend'    => true,
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            'User\Service\User' => 'User\Service\UserFactory',
        ],
        'invokables' => [
            'User\Mapper\User' => 'User\Mapper\User',
        ],
    ],
];
