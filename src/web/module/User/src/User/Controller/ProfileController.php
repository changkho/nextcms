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
use MongoDate;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ProfileController extends AbstractActionController
{
    /**
     * Edit the profile
     */
    public function editAction()
    {
        $user = Authentication::getInstance()->getIdentity();

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $user->first_name = $request->getPost('first_name');
            $user->last_name  = $request->getPost('last_name');
            $user->location   = $request->getPost('location');
            $user->bio        = $request->getPost('bio');
            $user->website    = $request->getPost('website');
            $user->gender     = $request->getPost('gender');
            $user->address    = [
                'street1'  => $request->getPost('street1'),
                'street2'  => $request->getPost('street2'),
                'city'     => $request->getPost('city'),
                'state'    => $request->getPost('state'),
                'country'  => $request->getPost('country'),
                'zip_code' => $request->getPost('zip_code'),
            ];

            $birthday         = implode('/', [$request->getPost('month'), $request->getPost('day'), $request->getPost('year')]);
            $user->birthday   = new MongoDate(strtotime(date($birthday)));

            /** @var \User\Service\User $userService */
            $userService = $this->getServiceLocator()->get('User\Service\User');
            $userService->save($user);

            Authentication::getInstance()->getStorage()->write($user);

            $this->flashMessenger()->addSuccessMessage($this->_('The profile is updated successfully'));
            $this->redirect()->toRoute('user\profile\edit');
        }

        $birthday = ($user->birthday instanceof MongoDate) ? date('m/d/Y', $user->birthday->sec) : date('m/d/Y');
        list($month, $day, $year) = explode('/', $birthday);
        return new ViewModel([
            'day'   => $day,
            'month' => $month,
            'user'  => $user,
            'year'  => $year,
        ]);
    }

    /**
     * Change the password
     */
    public function passwordAction()
    {
        $userId = (string) Authentication::getInstance()->getIdentity()->getId();

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Check if the current password is correct
            /** @var \User\Service\User $userService */
            $userService = $this->getServiceLocator()->get('User\Service\User');

            $currentPassword = $request->getPost('password');
            $newPassword     = $request->getPost('new_password');
            $retypePassword  = $request->getPost('retype_password');

            if (null == $newPassword || '' == $newPassword
                || null == $retypePassword || '' == $retypePassword
                || $newPassword != $retypePassword
            ) {
                $this->flashMessenger()->addErrorMessage($this->_('The new password and confirmation one are not match together'));
                $this->redirect()->toRoute('user\profile\password');
                return;
            }

            $user = $userService->findById($userId);

            if ('' == $currentPassword || $userService->verifyPassword($currentPassword, $user->password) == false) {
                $this->flashMessenger()->addErrorMessage($this->_('The current password you have typed is not correct'));
                $this->redirect()->toRoute('user\profile\password');
                return;
            }

            // Update the password
            $user->password = $newPassword;
            $userService->savePassword($user);

            // Sign out
            Authentication::getInstance()->clearIdentity();

            $this->flashMessenger()->addSuccessMessage($this->_('The password is updated successfully. You have to sign in again to continue'));
            $this->redirect()->toRoute('user\auth\signin');
        }

        return new ViewModel();
    }
}
