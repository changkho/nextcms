<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Mongo\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;

class ConnectorListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'setConnectorFactory'], 100);
    }

    public function setConnectorFactory(MvcEvent $e)
    {
        /** @var \Zend\ServiceManager\ServiceManager $serviceLocator */
        $serviceLocator = $e->getApplication()->getServiceManager();
        $params         = $e->getRouteMatch()->getParams();

        // User can set the connector via 'db' key
        if (isset($params['db'])) {
            $factory = $params['db'];
        } else {
            // Automatically connect to the master server if current route is backend, otherwise, connect to the slave one
            $factory = (isset($params['backend']) && $params['backend'])
                     ? 'Mongo\Service\MasterConnectorFactory'
                     : 'Mongo\Service\SlaveConnectorFactory';
        }

        $serviceLocator->setFactory('Mongo\Service\Connector', $factory);
    }
}
