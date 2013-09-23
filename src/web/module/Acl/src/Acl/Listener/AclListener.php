<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Listener;

use Acl\Service\Authentication;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;

class AclListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    const ERROR_FORBIDDEN = 'Acl\Listener\AclListener.error-not-allowed';

    public function attach(EventManagerInterface $events)
    {
        // I need to check the permission before performing any action
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'checkPermission'], 100);
    }

    public function checkPermission(MvcEvent $e)
    {
        $params = $e->getRouteMatch()->getParams();
        if (!isset($params['backend']) || !$params['backend']) {
            return;
        }

        $serviceLocator = $e->getApplication()->getServiceManager();
        $config         = $serviceLocator->get('config');

        if (Authentication::getInstance()->hasIdentity()) {
            // Check if user has permission to access the current page
            $user = Authentication::getInstance()->getIdentity();

            /** @var \Acl\Service\Acl $acl */
            $acl = $serviceLocator->get('Acl\Service\Acl');

            if (!$acl->isAllowed($user->role, $params['controller'], $params['action'])) {
                $e->setError(self::ERROR_FORBIDDEN);
                $e->getApplication()->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
            }
        } else {
            $request  = $e->getRequest();

            /** @var \Zend\Http\Response $response */
            $response = $e->getResponse();

            $router  = $e->getRouter();
            $url     = $router->assemble([], ['name' => $config['acl']['signin_route']]) . '?continue=' . $request->getUri()->toString();

            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);

            return $response;
        }
    }
}
