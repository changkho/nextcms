<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Controller\Plugin;

use Acl\Service\Authentication;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class IsAllowed extends AbstractPlugin
{
    public function __invoke($resource, $privilege)
    {
        if (!Authentication::getInstance()->hasIdentity()) {
            return false;
        }

        /** @var \Zend\Mvc\InjectApplicationEventInterface $controller */
        $controller = $this->getController();

        /** @var \Zend\Mvc\MvcEvent $event */
        $event = $controller->getEvent();

        $locator = $event->getApplication()->getServiceManager();

        /** @var \Acl\Service\Acl $acl */
        $acl = $locator->get('Acl\Service\Acl');
        return $acl->isAllowed(Authentication::getInstance()->getIdentity()->role, $resource, $privilege);
    }
}
