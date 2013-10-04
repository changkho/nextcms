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
use User\Entity\User;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    // --- Front-end ---

    /**
     * Check if an username or email has been used
     */
    public function checkAction()
    {
        $response = ['available' => false];

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            /** @var \User\Service\User $userService */
            $userService = $this->getServiceLocator()->get('User\Service\User');
            $userName    = $request->getPost('user_name');
            $email       = $request->getPost('email');

            if ($userName || $email) {
                $user                  = new User($userName ? ['user_name' => $userName] : ['email' => $email]);
                $response['available'] = $userService->exist($user) == false;
            }
        }

        return new JsonModel($response);
    }

    /**
     * Sign in
     */
    public function signinAction()
    {
        $locator = $this->getServiceLocator();

        /** @var \Zend\Http\Request $request */
        $request     = $this->getRequest();
        $queryString = $request->getQuery()->toString();
        $continue    = $request->getQuery('continue');

        if ($request->isPost()) {
            $userName = $request->getPost('username');
            $password = $request->getPost('password');

            /** @var \User\Service\User $userService */
            $userService = $locator->get('User\Service\User');
            $result      = $userService->authenticate($userName, $password);

            if (!$result->isValid()) {
                switch ($result->getCode()) {
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        $this->flashMessenger()->addErrorMessage($this->_('Not found user with given name'));
                        break;
                    case Result::FAILURE_CREDENTIAL_INVALID:
                        $this->flashMessenger()->addErrorMessage($this->_('Wrong username or password'));
                        break;
                    case Result::FAILURE_IDENTITY_AMBIGUOUS:
                        $this->flashMessenger()->addErrorMessage($this->_('The user has not been activated'));
                        break;
                    default:
                        $this->flashMessenger()->addErrorMessage($this->_('Cannot sign in'));
                        break;
                }

                $queryString ? $this->redirect()->toUrl($this->url()->fromRoute('user\auth\signin') . ($queryString ? '?' . $queryString : ''))
                             : $this->redirect()->toRoute('user\auth\signin');
            } else {
                $user = $result->getIdentity();
                Authentication::getInstance()->getStorage()->write($user);

                $this->getEventManager()->trigger('signin.success', $this, ['user' => $user]);
                if ($continue) {
                    $this->redirect()->toUrl(urldecode($continue));
                } else {
                    $this->redirect()->toUrl('/');
                }
            }
        }

        return new ViewModel([
            'continue'    => $continue,
            'queryString' => $queryString,
        ]);
    }

    /**
     * Sign out
     */
    public function signoutAction()
    {
        if (Authentication::getInstance()->hasIdentity()) {
            $user = Authentication::getInstance()->getIdentity();
            Authentication::getInstance()->clearIdentity();
            $this->getEventManager()->trigger('signout.success', $this, ['user' => $user]);
        }

        $this->redirect()->toUrl('/');
    }

    /**
     * Sign up
     */
    public function signupAction()
    {
        /** @var \Zend\Http\Request $request */
        $request     = $this->getRequest();

        $queryString = $request->getQuery()->toString();
        $continue    = $request->getQuery('continue');

        if ($request->isPost()) {
            $userName = $request->getPost('username', '');
            $email    = $request->getPost('email', '');
            $password = $request->getPost('password', '');

            if (trim($userName) == '' || trim($email) == '' || trim($password) == '') {
                $this->flashMessenger()->addErrorMessage($this->_('Username, email and password are required'));

                $queryString ? $this->redirect()->toUrl($this->url()->fromRoute('user\auth\signup') . ($queryString ? '?' . $queryString : ''))
                             : $this->redirect()->toRoute('user\auth\signup');
                return;
            }

            $serviceLocator = $this->getServiceLocator();

            /** @var \User\Service\User $userService */
            $userService = $serviceLocator->get('User\Service\User');

            $user = new User([
                'user_name'  => $userName,
                'email'      => $email,
                'password'   => $password,
                'role'       => 'member',
            ]);
            $result = $userService->create($user);

            if ($result != null) {
                // Mark user as authenticated
                Authentication::getInstance()->getStorage()->write($result);

                $this->getEventManager()->trigger('signup.success', $this, ['user' => $result]);

                if ($continue) {
                    $this->redirect()->toUrl(urldecode($continue));
                } else {
                    $this->redirect()->toUrl('/');
                }
            } else {
                $this->flashMessenger()->addErrorMessage($this->_('The registration is not successful'));
                $queryString ? $this->redirect()->toUrl($this->url()->fromRoute('user\auth\signup') . ($queryString ? '?' . $queryString : ''))
                             : $this->redirect()->toRoute('user\auth\signup');
            }
        }

        return new ViewModel([
            'continue'    => $continue,
            'queryString' => $queryString,
        ]);
    }
}
