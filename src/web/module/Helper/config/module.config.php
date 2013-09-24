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
    'view_helpers' => [
        'invokables' => [
            'address'        => 'Helper\View\Helper\Address',
            'flashMessenger' => 'Helper\View\Helper\FlashMessenger',
            'paginator'      => 'Helper\View\Helper\Paginator',
            'routeName'      => 'Helper\View\Helper\RouteName',
            'urlBuilder'     => 'Helper\View\Helper\UrlBuilder',
        ],
    ],
];
