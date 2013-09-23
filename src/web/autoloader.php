<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

define('APP_VENDOR_DIR', dirname(__DIR__) . '/vendor');

if (file_exists(APP_VENDOR_DIR . '/autoload.php')) {
	$loader = include APP_VENDOR_DIR . '/autoload.php';
}
if (isset($loader)) {
	$loader->add('Zend', APP_VENDOR_DIR . '/Zend');
} else {
	include APP_VENDOR_DIR . '/Zend/Loader/StandardAutoloader.php';
	include APP_VENDOR_DIR . '/Zend/Loader/AutoloaderFactory.php';
	Zend\Loader\AutoloaderFactory::factory([
		'Zend\Loader\StandardAutoloader' => [
			'autoregister_zf' => true,
			'namespaces'	  => [],
		],
	]);
}

set_include_path(PATH_SEPARATOR . APP_VENDOR_DIR . PATH_SEPARATOR . get_include_path());

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
	throw new RuntimeException('Cannot load ZF2 library!');
}
