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
        $app    = $e->getTarget();
        $events = $app->getEventManager();

        /** @var \Zend\View\HelperPluginManager $viewHelperManager */
        $viewHelperManager = $app->getServiceManager()->get('viewhelpermanager');

        $events->attach('dashboard\user', function(Event $e) use ($viewHelperManager) {
            $viewHelperManager->get('userDashboard')->__invoke();
        });
        $events->attach('sidebar\user', function(Event $e) use ($viewHelperManager) {
            $viewHelperManager->get('userSidebar')->__invoke();
        });
    }
}
