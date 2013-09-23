<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace I18n\Service;

use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TranslatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        // Get the list of modules
        /** @var \Zend\ModuleManager\ModuleManager $modules */
        $modules     = $serviceLocator->get('modulemanager');
        $modulesName = $modules->getModules();
        $files       = [];
        foreach ($modulesName as $module) {
            if (isset($config[strtolower($module)]['language_dir'])) {
                $file = $config[strtolower($module)]['language_dir'] . '/' . $locale . '.php';

                if (file_exists($file)) {
                    $files[] = [
                        'type'	      => 'phparray',
                        'filename'    => $file,
                        'text_domain' => $module,
                        'locale'      => $locale,
                    ];
                }
            }
        }
        return Translator::factory(['translation_files' => $files]);
    }
}
