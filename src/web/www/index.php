<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

// The root directory
define('APP_ROOT_DIR', dirname(__DIR__));
chdir(APP_ROOT_DIR);

// Environment variables
define('APP_ENV',  (getenv('APPLICATION_ENV')  ?: 'dev'));
define('APP_NAME', (getenv('APPLICATION_NAME') ?: 'application'));

// Template dir
define('APP_TEMPLATE_DIR', APP_ROOT_DIR . '/template');

// Setup auto loading
require_once APP_ROOT_DIR . '/autoloader.php';

// Run the app
Zend\Mvc\Application::init(require APP_ROOT_DIR . '/config/application.config.php')->run();
