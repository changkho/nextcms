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
            'Hello\Controller\Dashboard' => 'Hello\Controller\DashboardController',
            'Hello\Controller\Index'     => 'Hello\Controller\IndexController',
        ],
    ],

    'router' => [
        'routes' => [
            'hello\index\index' => [
                'type'	  => 'literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Hello\Controller\Index',
                        'action'     => 'index',
                    ],
                ],
            ],

            // Dashboard
            'hello\dashboard\index' => [
                'type'	  => 'literal',
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => 'Hello\Controller\Dashboard',
                        'action'     => 'index',
                        'backend'    => true,
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'display_exceptions' => true,
        'doctype'            => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'error/404'   => APP_TEMPLATE_DIR . '/error/404.phtml',
            'error/index' => APP_TEMPLATE_DIR . '/error/index.phtml',
        ],
        'template_path_stack' => [
            APP_TEMPLATE_DIR,
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
