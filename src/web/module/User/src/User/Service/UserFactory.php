<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace User\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Mongo\Db $db */
        $db = $serviceLocator->get('Mongo\Service\Connector');

        /** @var \User\Mapper\User $userMapper */
        $userMapper = $serviceLocator->get('User\Mapper\User');
        $userMapper->setDb($db);

        $userService = new User();
        $userService->setUserMapper($userMapper);

        return $userService;
    }
}
