<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace User\Controller;

use Acl\Service\Authentication;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\Null;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ManagerController extends AbstractActionController
{
    /**
     * Activate/deactivate user
     */
    public function activateAction()
    {
        $user = Authentication::getInstance()->getIdentity();

        /** @var \User\Service\User $userService */
        $userService = $this->getServiceLocator()->get('User\Service\User');

        /** @var \Zend\Http\Request $request */
        $request  = $this->getRequest();
        $response = ['result' => 'ERROR'];
        if ($request->isPost()) {
            $userId = $request->getPost('id');
            if ((string) $user->getId() != $userId) {
                $response['result'] = $userService->activate($userId) ? 'OK' : 'ERROR';
            }
        }

        return new JsonModel($response);
    }

    /**
     * List users
     */
    public function listAction()
    {
        /** @var \User\Entity\User $user */
        $user           = Authentication::getInstance()->getIdentity();
        $serviceLocator = $this->getServiceLocator();

        /** @var \User\Service\User $userService */
        $userService = $serviceLocator->get('User\Service\User');

        $perPage  = 15;
        $page     = $this->params()->fromQuery('page', 1);
        $status   = $this->params()->fromQuery('status', null);
        $keyword  = $this->params()->fromQuery('q', null);

        $criteria = [];
        if ($status != null) {
            $criteria['status'] = $status;
        }
        if ($keyword) {
            $criteria['keyword'] = $keyword;
        }

        $users = $userService->find($criteria, ($page - 1) * $perPage, $perPage);
        $total = $userService->count($criteria);

        $paginator = new Paginator(new Null($total));
        $paginator->setCurrentPageNumber($page)
                  ->setItemCountPerPage($perPage)
                  ->setPageRange(5);

        return new ViewModel([
            'keyword'   => $keyword ?: '',
            'paginator' => $paginator,
            'total'     => $total,
            'status'    => $status,
            'user'      => $user,
            'users'     => $users,
        ]);
    }
}
