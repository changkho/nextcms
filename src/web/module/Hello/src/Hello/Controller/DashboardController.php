<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Hello\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DashboardController extends AbstractActionController
{
    /**
     * Administrator dashboard
     */
    public function indexAction()
    {
        /** @var \Zend\ServiceManager\ServiceManager $serviceLocator */
        $serviceLocator = $this->getServiceLocator();

        /** @var \Zend\ModuleManager\ModuleManager $moduleManager */
        $moduleManager = $serviceLocator->get('moduleManager');
        $modules       = $moduleManager->getModules();

        return new ViewModel([
            'eventManager' => $this->getEventManager(),
            'modules'      => $modules,
        ]);
    }
}
