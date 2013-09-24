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

use Zend\Mvc\Controller\Plugin\FlashMessenger as FlashMessengerPlugin;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;

class FlashMessenger extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __invoke($filter = null)
    {
        /** @var \Zend\View\HelperPluginManager $helperManager */
        $helperManager = $this->serviceLocator;

        /** @var \Zend\ServiceManager\ServiceManager $locator */
        $locator = $helperManager->getServiceLocator();

        /** @var \Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger */
        $flashMessenger = $locator->get('controllerPluginManager')->get('flashMessenger');

        $flashMessages = [];
        foreach ([FlashMessengerPlugin::NAMESPACE_DEFAULT, FlashMessengerPlugin::NAMESPACE_INFO, FlashMessengerPlugin::NAMESPACE_ERROR, FlashMessengerPlugin::NAMESPACE_SUCCESS] as $namespace) {
            $msg = $flashMessenger->getMessagesFromNamespace($namespace);
            if (count($msg) > 0) {
                $flashMessages[$namespace] = $msg;
            }
        }

        switch ($filter) {
            case FlashMessengerPlugin::NAMESPACE_DEFAULT:
            case FlashMessengerPlugin::NAMESPACE_INFO:
            case FlashMessengerPlugin::NAMESPACE_ERROR:
            case FlashMessengerPlugin::NAMESPACE_SUCCESS:
                $messages = [
                    $filter => $flashMessages[$filter],
                ];
                break;
            default:
                $messages = $flashMessages;
                break;
        }

        return $this->view->render('helper/_partial/flashMessages.phtml', [
            'messages' => $messages,
            // Map filter with Alert class
            'classMap' => [
                FlashMessengerPlugin::NAMESPACE_DEFAULT => 'info',
                FlashMessengerPlugin::NAMESPACE_INFO    => 'info',
                FlashMessengerPlugin::NAMESPACE_ERROR   => 'danger',
                FlashMessengerPlugin::NAMESPACE_SUCCESS => 'success',
            ],
        ]);
    }
}
