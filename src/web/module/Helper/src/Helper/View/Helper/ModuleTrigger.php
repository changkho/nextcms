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

class ModuleTrigger extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __invoke($eventPrefix, $module = null)
    {
        /** @var \Zend\View\HelperPluginManager $helperManager */
        $helperManager = $this->serviceLocator;

        /** @var \Zend\ServiceManager\ServiceManager $serviceLocator */
        $serviceLocator = $helperManager->getServiceLocator();

        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('application');

        if (null == $module) {
            $controller = $app->getMvcEvent()->getRouteMatch()->getParam('controller');
            $module     = explode('\\', $controller)[0];
        }

        $app->getEventManager()->trigger($eventPrefix . '\\' . lcfirst($module), null, ['module' => $module]);
    }
}
