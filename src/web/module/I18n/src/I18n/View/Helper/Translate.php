<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace I18n\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\I18n\View\Helper\Translate as TranslateHelper;

class Translate extends TranslateHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __invoke($message, $textDomain = null, $locale = null)
    {
        if ($textDomain == null) {
            // Try to get the current module's name

            /** @var \Zend\View\HelperPluginManager $helperManager */
            $helperManager = $this->serviceLocator;

            /** @var \Zend\ServiceManager\ServiceManager $locator */
            $locator = $helperManager->getServiceLocator();

            /** @var \Zend\Mvc\Application $app */
            $app     = $locator->get('application');
            $route   = $app->getMvcEvent()->getRouteMatch();
            if ($route) {
                $controller = $route->getParam('controller');
                $textDomain = explode('\\', $controller)[0];
            }
        }

        return parent::__invoke($message, $textDomain, $locale);
    }
}
