<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Provider\Role;

use Acl\Provider\Role;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

class Db implements ProviderInterface
{
    use ServiceLocatorAwareTrait;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getRoles()
    {
        /** @var \Mongo\Db $db */
        $db = $this->serviceLocator->get('Mongo\Service\MasterConnector');

        /** @var \Acl\Mapper\Role $roleMapper */
        $roleMapper = $this->serviceLocator->get('Acl\Mapper\Role');
        $roleMapper->setDb($db);

        $roles = $roleMapper->find();

        if ($roles == null) {
            return [];
        }

        $return = [];
        foreach ($roles as $role) {
            $r = new Role($role->name);
            $r->setParents($role->parent);
            $return[] = $r;
        }
        return $return;
    }
}
