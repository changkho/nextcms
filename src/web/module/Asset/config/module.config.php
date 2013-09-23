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
        'factories' => [
            'assetCdn'        => 'Asset\View\Helper\Service\CdnFactory',
            'assetHeadLink'   => 'Asset\View\Helper\Service\HeadLinkFactory',
            'assetHeadScript' => 'Asset\View\Helper\Service\HeadScriptFactory',
        ],
        'aliases' => [
            'cdn'        => 'assetCdn',
            'headLink'   => 'assetHeadLink',
            'headScript' => 'assetHeadScript',
        ],
    ],
];
