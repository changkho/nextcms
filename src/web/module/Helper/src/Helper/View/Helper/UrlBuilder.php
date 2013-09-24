<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Helper\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Uri\Http;
use Zend\View\Helper\AbstractHelper;

class UrlBuilder extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __invoke($routeName, $params = [], $excludedParams = [])
    {
        /** @var \Zend\View\HelperPluginManager $helperManager */
        $helperManager = $this->serviceLocator;

        /** @var \Zend\ServiceManager\ServiceManager $locator */
        $locator = $helperManager->getServiceLocator();

        /** @var \Zend\Mvc\Router\SimpleRouteStack $router */
        $router = $locator->get('Router');

        $assembledParams = [];
        if ($router->hasRoute($routeName)) {
            /** @var \Zend\Mvc\Router\Http\RouteInterface $route */
            $route  = $router->getRoute($routeName);
            $assembledParams = $route->getAssembledParams();
        }

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $locator->get('request');
        $uri     = clone $request->getUri();
        $query   = [];
        foreach (array_merge($uri->getQueryAsArray(), $params) as $key => $value) {
            if ($value != '') {
                $query[$key] = $value;
            }
        }
        $uri->setQuery($query);

        /** @var \Zend\View\Helper\Url $urlViewHelper */
        $urlViewHelper = $this->view->plugin('url');
        $return        = $urlViewHelper->__invoke($routeName, $query);

        $query = $uri->getQueryAsArray();
        foreach ($uri->getQueryAsArray() as $key => $value) {
            if (in_array($key, $assembledParams) || in_array($key, $excludedParams)) {
                unset($query[$key]);
            }
        }
        $uri->setQuery($query);

        return (count($query) == 0) ? $return : $return . '?' . Http::encodeQueryFragment($uri->getQuery());
    }
}
