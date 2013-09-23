<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Service;

use Acl\Guard\AbstractControllerGuard;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GuardFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $guards = [];

        if (isset($config['acl']['guards']['Acl\Guard\Controller'])) {
            // Map the invokables controller with real controller class
            $controllers      = $config['controllers']['invokables'];

            $controllerGuards = [];
            foreach ($config['acl']['guards']['Acl\Guard\Controller'] as $item) {
                list($controller, $action, $guardClass) = $item;
                $controllerGuards[$guardClass][$controller]               = $action;
                $controllerGuards[$guardClass][$controllers[$controller]] = $action;
            }

            foreach ($controllerGuards as $class => $actions) {
                if (!class_exists($class)) {
                    throw new \Exception(sprintf('The %s class is not found', $class));
                }
                $guard = new $class($actions);
                if (!$guard instanceof AbstractControllerGuard) {
                    throw new \Exception(sprintf('The %s class is not instance of \Acl\Guard\AbstractControllerGuard', $class));
                }
                $guards[] = $guard;
            }
        }

        return $guards;
    }
}
