<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */
namespace Acl\Provider\Resource;

use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

class Db implements ProviderInterface
{
    use ServiceLocatorAwareTrait;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getResources()
    {
        /** @var \Mongo\Db $db */
        $db = $this->serviceLocator->get('Mongo\Service\MasterConnector');

        /** @var \Acl\Mapper\Resource $resourceMapper */
        $resourceMapper = $this->serviceLocator->get('Acl\Mapper\Resource');
        $resourceMapper->setDb($db);

        $resources = $resourceMapper->find();
        if ($resources == null) {
            return [];
        }

        $return = [];
        foreach ($resources as $resource) {
            $return[] = new GenericResource($resource->controller);
        }
        return $return;
    }
}
