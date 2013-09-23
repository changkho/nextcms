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

use Acl\Entity\Rule;

class Config implements ProviderInterface
{
    /**
     * @var array
     */
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getRules()
    {
        $rules = [];
        foreach (['allow', 'deny'] as $allowOrDeny) {
            if (isset($this->options[$allowOrDeny])) {
                foreach ($this->options[$allowOrDeny] as $index => $value) {
                    list($roles, $resource, $privileges) = $value;
                    $roles      = is_array($roles) ? $roles : [$roles];
                    $privileges = is_array($privileges) ? $privileges : [$privileges];

                    foreach ($roles as $roleName) {
                        foreach ($privileges as $privilege) {
                            $rules[] = new Rule([
                                'resource'  => $resource,
                                'obj_id'    => $roleName,
                                'obj_type'  => 'role',
                                'privilege' => $privilege,
                                'allow'     => ('allow' == $allowOrDeny),
                            ]);
                        }
                    }
                }
            }
        }

        return $rules;
    }
}
