<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace I18n;

use Locale;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, BootstrapListenerInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
	{
		return [
			'Zend\Loader\StandardAutoloader' => [
				'namespaces' => [
                    __NAMESPACE__ => __DIR__,
				],
			],
		];
	}

    public function getConfig()
    {
        return include dirname(dirname(__DIR__)) . '/config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        /** @var \Zend\Mvc\Application $app */
        $app     = $e->getApplication();
        $config  = $app->getConfig();

        // Try to get the locale from request. If it is not defined, get the default one from config
        $router        = $app->getMvcEvent()->getRouteMatch();
        $defaultLocale = $config['i18n']['locale'];
        $locale        = ($router == null) ? $defaultLocale : $router->getParam('locale', $defaultLocale);

        Locale::setDefault($locale);
    }
}
