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

use Zend\View\Helper\AbstractHelper;

class Paginator extends AbstractHelper
{
    public function __invoke(\Zend\Paginator\Paginator $paginator = null, $route = null, $routeParams = null, $excludedParams = [])
    {
        /** @var \Zend\View\Helper\PaginationControl $paginationControl */
        $paginationControl = $this->view->plugin('PaginationControl');

        return $paginationControl->__invoke($paginator, 'Sliding', 'helper/_partial/slidingPaginator.phtml', [
            'route'          => $route,
            'routeParams'    => $routeParams,
            'excludedParams' => $excludedParams,
        ]);
    }
}
