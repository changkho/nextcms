<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Hello\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

class Sidebar extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Show the sidebar of current module
     */
    public function __invoke()
    {
        /** @var \Zend\View\HelperPluginManager $helperManager */
        $helperManager = $this->serviceLocator;

        /** @var \Zend\ServiceManager\ServiceManager $serviceLocator */
        $serviceLocator = $helperManager->getServiceLocator();

        /** @var \Zend\Mvc\Application $app */
        $app   = $serviceLocator->get('application');
        $route = $app->getMvcEvent()->getRouteMatch();

        if ($route) {
            $controller = $route->getParam('controller');
            $module     = explode('\\', $controller)[0];
            $app->getEventManager()->trigger('sidebar.' . lcfirst($module), $this, ['module' => $module]);
        }
    }
}
