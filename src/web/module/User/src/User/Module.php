<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace User;

use Zend\EventManager\Event;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements  AutoloaderProviderInterface, BootstrapListenerInterface, ConfigProviderInterface
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
        $app = $e->getTarget();

        /** @var \Zend\View\HelperPluginManager $viewHelperManager */
        $viewHelperManager = $app->getServiceManager()->get('viewhelpermanager');

        $app->getEventManager()->getSharedManager()->attach('Hello\Controller\DashboardController', 'dashboard.user', function(Event $e) use ($viewHelperManager) {
            $viewHelperManager->get('userDashboard')->__invoke();
        });

        $app->getEventManager()->attach('sidebar.user', function(Event $e) use ($viewHelperManager) {
            $viewHelperManager->get('userSidebar')->__invoke();
        });
    }
}
