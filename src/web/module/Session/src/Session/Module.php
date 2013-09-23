<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Session;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

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
        $app           = $e->getTarget();
        $config        = $app->getConfig();
        $sessionConfig = new SessionConfig();
        if (isset($config['session']['options'])) {
            $options = $config['session']['options'];
            if (isset($options['cookie_domain']) && strpos($_SERVER['SERVER_NAME'], $options['cookie_domain']) === false) {
                $options['cookie_domain'] = $_SERVER['SERVER_NAME'];
            }

            $sessionConfig->setOptions($options);
        }

        $serviceManager = $app->getServiceManager();
        $storage        = null;
        $saveHandler    = null;

        if ($serviceManager->has('Session\Service\Storage', false)) {
            /** @var \Zend\Session\Storage\StorageInterface $storage */
            $storage = $serviceManager->get('Session\Service\Storage');
        }

        if ($serviceManager->has('Session\Service\SaveHandler', false)) {
            /** @var \Zend\Session\SaveHandler\SaveHandlerInterface $saveHandler */
            $saveHandler = $serviceManager->get('Session\Service\SaveHandler');
        }

        Container::setDefaultManager(new SessionManager($sessionConfig, $storage, $saveHandler));
    }
}
