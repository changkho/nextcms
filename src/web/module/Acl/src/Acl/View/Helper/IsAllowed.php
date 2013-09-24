<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\View\Helper;

use Acl\Service\Authentication;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

class IsAllowed extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected $cacheAllowed = [];

    public function __invoke($resource, $privilege)
    {
        if (!Authentication::getInstance()->hasIdentity()) {
            return false;
        }

        if (isset($this->cacheAllowed[$resource][$privilege])) {
            return $this->cacheAllowed[$resource][$privilege];
        }

        /** @var \Zend\View\HelperPluginManager $helperManager */
        $helperManager = $this->serviceLocator;

        /** @var \Zend\ServiceManager\ServiceManager $serviceLocator */
        $serviceLocator = $helperManager->getServiceLocator();

        /** @var \Acl\Service\Acl $acl */
        $acl       = $serviceLocator->get('Acl\Service\Acl');
        $isAllowed = $acl->isAllowed(Authentication::getInstance()->getIdentity()->role, $resource, $privilege);

        // Cache user's privilege
        // So if the view helper is called many times on same page, it can return the value taken from cache
        $this->cacheAllowed[$resource][$privilege] = $isAllowed;

        return $isAllowed;
    }
}
