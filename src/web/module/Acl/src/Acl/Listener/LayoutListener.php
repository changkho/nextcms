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

use Acl\Guard\AbstractControllerGuard;
use Acl\Service\Authentication;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;

/**
 * Switch the layout when user access the back-end
 */
class LayoutListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        // Set priority greater than 1
        // so layout can be disabled in controller action if I want
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH,       [$this, 'updateLayout'], 100);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'keepLayout']);
    }

    /**
     * Switch layout based on user's role
     *
     * @param MvcEvent $e
     */
    public function updateLayout(MvcEvent $e)
    {
        $params = $e->getRouteMatch()->getParams();

        if (isset($params['backend'])
            && $params['backend']
            && Authentication::getInstance()->hasIdentity()
        ) {
            $config  = $e->getApplication()->getServiceManager()->get('config');
            $role    = Authentication::getInstance()->getIdentity()->role;
            $layouts = $config['acl']['backend_layout'];

            if ($role && isset($layouts['roles'][$role])) {
                $e->getViewModel()->setTemplate($layouts['roles'][$role]);
            } else {
                $e->getViewModel()->setTemplate($layouts['default']);
            }
        }
    }

    /**
     * Keep the same layout if user access forbidden page
     *
     * @param MvcEvent $e
     */
    public function keepLayout(MvcEvent $e)
    {
        $error = $e->getError();
        if (empty($error) || in_array($error, [AclListener::ERROR_FORBIDDEN, AbstractControllerGuard::ERROR_FORBIDDEN])) {
            $this->updateLayout($e);
        }
    }
}
