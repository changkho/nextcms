<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Helper\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

/**
 * Return the name of current route
 */
class RouteName extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /** @var string */
    protected $routeName;

    public function __invoke()
    {
        if ($this->routeName) {
            return $this->routeName;
        }

        /** @var \Zend\View\HelperPluginManager $helperManager */
        $helperManager = $this->serviceLocator;

        /** @var \Zend\ServiceManager\ServiceManager $serviceLocator */
        $serviceLocator = $helperManager->getServiceLocator();

        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('application');
        $this->routeName = $app->getMvcEvent()->getRouteMatch()->getMatchedRouteName();

        return $this->routeName;
    }
}
