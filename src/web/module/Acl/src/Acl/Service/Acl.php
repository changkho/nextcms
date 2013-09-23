<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Service;

use Zend\Permissions\Acl\Acl as BaseAcl;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Acl implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var bool
     */
    protected $loaded = false;

    /**
     * @var \Zend\Permissions\Acl\Acl
     */
    protected $acl;

    public function __construct()
    {
        $this->acl = new BaseAcl();
    }

    /**
     * Check if a role has privilege to access given resource
     *
     * @param string $role
     * @param string $resource
     * @param string $privilege
     * @return bool
     */
    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        $this->_load();
        return $this->acl->isAllowed($role, $resource, $privilege);
    }

    private function _load()
    {
        if ($this->loaded == false) {
            // Add roles
            $config = $this->serviceLocator->get('config');
            if (isset($config['acl']['role_providers'])) {
                $roles = [];

                foreach ($config['acl']['role_providers'] as $class => $options) {
                    /** @var \Acl\Provider\Role\ProviderInterface $roleProvider */
                    $roleProvider = $this->serviceLocator->get($class);
                    $roles        = $roles + $roleProvider->getRoles();
                }

                foreach ($roles as $role) {
                    /** @var \Acl\Entity\Role $role */
                    $this->acl->addRole($role, $role->getParents());
                }
            }

            // Add resources
            if (isset($config['acl']['resource_providers'])) {
                foreach ($config['acl']['resource_providers'] as $class => $options) {
                    /** @var \Acl\Provider\Resource\ProviderInterface $resourceProvider */
                    $resourceProvider = $this->serviceLocator->get($class);
                    $resources        = $resourceProvider->getResources();
                    if ($resources) {
                        foreach ($resources as $r) {
                            if (!$this->acl->hasResource($r)) {
                                $this->acl->addResource($r);
                            }
                        }
                    }
                }
            }

            // Add rules
            if (isset($config['acl']['rule_providers'])) {
                $rules = [];

                foreach ($config['acl']['rule_providers'] as $class => $options) {
                    /** @var \Acl\Provider\Rule\ProviderInterface $ruleProvider */
                    $ruleProvider = $this->serviceLocator->get($class);
                    $rules        = $rules + $ruleProvider->getRules();
                }

                foreach ($rules as $rule) {
                    /** @var \Acl\Entity\Rule $rule */
                    if ($rule->allow) {
                        $this->acl->allow($rule->obj_id, $rule->resource, $rule->privilege);
                    } else {
                        $this->acl->deny($rule->obj_id, $rule->resource, $rule->privilege);
                    }
                }
            }

            $this->loaded = true;
        }
    }
}
