<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\Provider\Rule;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

class Db implements ProviderInterface
{
    use ServiceLocatorAwareTrait;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getRules()
    {
        /** @var \Mongo\Db $db */
        $db = $this->serviceLocator->get('Mongo\Service\MasterConnector');

        /** @var \Acl\Mapper\Rule $ruleMapper */
        $ruleMapper = $this->serviceLocator->get('Acl\Mapper\Rule');
        $ruleMapper->setDb($db);

        $rules = $ruleMapper->find();
        return $rules ?: [];
    }
}
