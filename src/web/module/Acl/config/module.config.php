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
    'controller_plugins' => [
        'invokables' => [
            'isAllowed' => 'Acl\Controller\Plugin\IsAllowed',
        ],
    ],

    'service_manager' => [
        'invokables' => [
            // Mapper
            'Acl\Mapper\Resource' => 'Acl\Mapper\Resource',
            'Acl\Mapper\Role'     => 'Acl\Mapper\Role',
            'Acl\Mapper\Rule'     => 'Acl\Mapper\Rule',

            // Permission
            'Acl\Service\Acl' => 'Acl\Service\Acl',
        ],
        'factories' => [
            'Acl\Provider\Resource\Config' => 'Acl\Service\ConfigResourceProviderFactory',
            'Acl\Provider\Resource\Db'     => 'Acl\Service\DbResourceProviderFactory',
            'Acl\Provider\Role\Config'     => 'Acl\Service\ConfigRoleProviderFactory',
            'Acl\Provider\Role\Db'         => 'Acl\Service\DbRoleProviderFactory',
            'Acl\Provider\Rule\Config'     => 'Acl\Service\ConfigRuleProviderFactory',
            'Acl\Provider\Rule\Db'         => 'Acl\Service\DbRuleProviderFactory',

            // Guards
            'Acl\Service\Guard'            => 'Acl\Service\GuardFactory',
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'identity'  => 'Acl\View\Helper\Identity',
            'isAllowed' => 'Acl\View\Helper\IsAllowed',
        ],
    ],
];
