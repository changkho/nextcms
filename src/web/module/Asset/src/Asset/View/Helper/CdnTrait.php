<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Asset\View\Helper;

use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\Uri\Http;

trait CdnTrait
{
    /**
     * CDN options
     *
     * @var array
     */
    protected $cdnOptions;

    /**
     * The Id of CDN server
     * @var int
     */
    protected static $serverId;

    public function setCdnOptions($cdnOptions)
    {
        if (empty($cdnOptions) || !is_array($cdnOptions)) {
            throw new InvalidArgumentException('CDN options has to be an array');
        }
        $this->cdnOptions = $cdnOptions;
        static::$serverId = 0;
    }

    protected function appendCdn($href)
    {
        $uri = new Http($href);
        if ($uri->getHost()) {
            return $href;
        }

        $servers = $this->cdnOptions['servers'];

        if (1 == count($servers)) {
            $server = $servers[0];
        } else {
            switch ($this->cdnOptions['method']) {
                case 'respective':
                    if (!isset($servers[static::$serverId])) {
                        static::$serverId = 0;
                    }
                    $server = $servers[static::$serverId];
                    static::$serverId++;
                    break;

                // Get a random CDN server
                case 'random':
                default:
                    $server = $servers[array_rand($servers)];
                    break;
            }
        }

        $href = rtrim($server, '/') . '/' . ltrim($href, '/');
        return $href;
    }
}
