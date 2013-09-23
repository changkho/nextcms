<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Guard;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;

abstract class AbstractControllerGuard implements GuardInterface
{
    use ListenerAggregateTrait;

    /** @var array */
    protected $actions;

    const ERROR_FORBIDDEN = 'Acl\Guard\AbstractControllerGuard.error-not-allowed';

    /**
     * @param array $actions Map the controller with actions that need to be checked permission before dispatching
     * Can be one of three kinds as following:
     * 'controller' => 'action'     : An action
     * 'controller' => ['action']   : Array of actions
     * 'controller' => null         : All actions of controller will be checked
     */
    public function __construct($actions = [])
    {
        $this->actions = $actions;
    }

    public function attach(EventManagerInterface $events)
    {
        if (is_array($this->actions)) {
            foreach ($this->actions as $controller => $actions) {
                // Use PHP_INT_MAX to ensure this listener has greater priority than any controller listeners
                $this->listeners[] = $events->getSharedManager()->attach($controller, MvcEvent::EVENT_DISPATCH, [$this, 'checkPermission'], PHP_INT_MAX);
            }
        }
    }

    public function checkPermission(MvcEvent $e)
    {
        // Get current controller and action
        $params     = $e->getRouteMatch()->getParams();
        $controller = $params['controller'];
        $action     = $params['action'];

        switch (true) {
            case is_string($this->actions[$controller]):
                $needToCheck = ($action == $this->actions[$controller]);
                break;
            case is_array($this->actions[$controller]):
                $needToCheck = in_array($action, $this->actions[$controller]);
                break;
            case is_null($this->actions[$controller]):
                $needToCheck = true;
                break;
            default:
                $needToCheck = false;
                break;
        }

        if ($needToCheck && !$this->isAllowed($e, $action)) {
            // User is not allowed to perform the controller action with current request parameters
            $e->setError(self::ERROR_FORBIDDEN);
            $e->getApplication()->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
            $e->stopPropagation(true);
        }
    }

    abstract protected function isAllowed(MvcEvent $e, $action);
}
