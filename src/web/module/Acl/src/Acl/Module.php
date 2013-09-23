<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl;

use Acl\Listener\AclListener;
use Acl\Listener\LayoutListener;
use Acl\View\ForbiddenStrategy;
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
        $app            = $e->getTarget();
        $serviceLocator = $app->getServiceManager();
        $config         = $serviceLocator->get('config');

        $events = $app->getEventManager();
        $events->attach(new AclListener());
        $events->attach(new LayoutListener());

        if (isset($config['acl']['forbidden_template'])) {
            $events->attach(new ForbiddenStrategy($config['acl']['forbidden_template']));
        }

        // Attach guards
        if (isset($config['acl']['guards'])) {
            /** @var \Acl\Guard\GuardInterface[] $guards */
            $guards = $serviceLocator->get('Acl\Service\Guard');
            if ($guards) {
                foreach ($guards as $guard) {
                    $events->attach($guard);
                }
            }
        }
    }
}
