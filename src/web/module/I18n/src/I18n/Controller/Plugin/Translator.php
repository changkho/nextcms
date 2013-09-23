<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace I18n\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Translator extends AbstractPlugin
{
    public function __invoke($message, $domain = null)
    {
        /** @var \Zend\Mvc\Controller\AbstractActionController $controller */
        $controller = $this->getController();

        /** @var \Zend\I18n\Translator\Translator $translator */
        $translator = $controller->getServiceLocator()->get('translator');

        if (null == $domain) {
            $domain = explode('\\', get_class($controller))[0];
        }

        return $translator->translate($message, $domain);
    }
}
