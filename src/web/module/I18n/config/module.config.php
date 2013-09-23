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
            'translator' => 'I18n\Controller\Plugin\Translator',
        ],
        'aliases' => [
            '_' => 'translator',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'translator' => 'I18n\Service\TranslatorFactory',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'i18nLocale' => 'I18n\View\Helper\Locale',
            'translate'  => 'I18n\View\Helper\Translate',
        ],
        'aliases' => [
            '_' => 'translate',
        ],
    ],
];
