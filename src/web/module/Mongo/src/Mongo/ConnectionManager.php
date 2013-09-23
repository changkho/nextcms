<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Mongo;

use MongoClient;

class ConnectionManager
{
	const SERVER_SLAVE  = 'slave';
	const SERVER_MASTER = 'master';
	
	/**
	 * @var ConnectionManager
	 */
	protected static $instance;
	
	/**
	 * Array of servers
	 * <code>
	 *	[
	 *		'slave'  => [
	 *			'slave1' => [
	 *				'username' => ...,
	 *				'password' => ...,
	 *				'db'	   => ...,
	 *				'host'	   => ...,	// localhost, by default
	 *				'port'	   => ...,	// 27017, by default
	 *			],
	 *          ...
	 *		],
	 *		'master' => [
	 *			'master1' => [
	 *				'username' => ...,
	 *				'password' => ...,
	 *				'db'	   => ...,
	 *				'host'	   => ...,
	 *				'port'	   => ...,
	 *			],
	 *          ...
	 *		],
	 *	]
	 * </code>
	 * @var array
	 */
	protected $servers = [];
	
	/**
	 * Get the instance
	 * 
	 * @return \Mongo\ConnectionManager
	 */
	public static function getInstance()
	{
		if (null == self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Set database servers
	 * 
	 * @param array $servers
	 * @return \Mongo\ConnectionManager
	 */
	public function setServers($servers)
	{
		$this->servers = $servers;
		return $this;
	}
	
	/**
	 * Get database servers
	 * 
	 * @return array
	 */
	public function getServers()
	{
		return $this->servers;
	}
	
	/**
	 * Connect to a slave server
	 * 
	 * @param string $serverName The name of slave server. If it is not defined, a random server will be chosen
	 * @return \MongoDB
	 */
	public function connectToSlave($serverName = null)
	{
		return $this->connectTo(self::SERVER_SLAVE, $serverName);
	}
	
	/**
	 * Connect to a master server
	 *
	 * @param string $serverName The name of master server. If it is not defined, a random server will be chosen
	 * @return \MongoDB
	 */
	public function connectToMaster($serverName = null)
	{
		return $this->connectTo(self::SERVER_MASTER, $serverName);
	}
	
	/**
	 * Connect to MongoDB server
	 *
	 * @param string $type Can be ConnectionManager::SERVER_MASTER or ConnectionManager::SERVER_SLAVE
	 * @param string $serverName The name of server. If it is not defined, a random server will be chosen
     * @throws \Exception
	 * @return \MongoDB
	 */
	public function connectTo($type = self::SERVER_MASTER, $serverName = null)
	{
		if (!isset($this->servers[$type])) {
			throw new \Exception('There are not any ' . $type . ' servers');
		}
		if (null == $serverName) {
			// Choose a server randomly
			$serverName = array_rand($this->servers[$type]);
		}
		if (!isset($this->servers[$type][$serverName])) {
			throw new \Exception('Cannot find the ' . $serverName . ' server');
		}
		return $this->connect($this->servers[$type][$serverName]);
	}
	
	/**
	 * Connect to given MongoDB server
	 * 
	 * @param array $options The connection options, including the following key:
	 * - username
	 * - password
	 * - db
	 * - server (optional, "localhost" by default)
	 * - port (optional, 27017 by default)
     * @throws \Exception
	 * @return \Mongo\Db
	 */
	public function connect($options = [])
	{
		$options = array_merge($options, [
			'server' => 'localhost',
			'port'   => 27017,
		]);
		$connString = 'mongodb://' . $options['server'] . ':' . $options['port'];
        if (!isset($options['db']) || null == $options['db'] || '' == $options['db']) {
            throw new \Exception('The Mongo database name is not defined');
        }

        try {
            /** @var $mongo \Mongo */
            $mongo = new MongoClient($connString);
            return new Db($mongo, $options['db']);
        } catch (\Exception $ex) {
            return null;
        }
	}
}
