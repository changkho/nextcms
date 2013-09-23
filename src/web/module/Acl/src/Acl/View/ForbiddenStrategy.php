<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Acl\View;

use Acl\Guard\AbstractControllerGuard;
use Acl\Listener\AclListener;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Send 403 Forbidden status code, and render 403 view script if user is not allowed to access the current URI
 */
class ForbiddenStrategy implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError']);
    }

    public function onDispatchError(MvcEvent $e)
    {
        // Do nothing if no error in the event
        $error = $e->getError();
        if (empty($error) || !in_array($error, [AclListener::ERROR_FORBIDDEN, AbstractControllerGuard::ERROR_FORBIDDEN])) {
            return;
        }

        $result   = $e->getResult();
        /** @var \Zend\Http\Request $request */
        $request  = $e->getRequest();
        $response = $e->getResponse();

        // Do nothing if the result is a response object
        if ($result instanceof ResponseInterface || ($response && ! $response instanceof Response)) {
            return;
        }

        $model = $request->isXmlHttpRequest() ? new JsonModel(['result' => 'FORBIDDEN']) : new ViewModel();
        $model->setTemplate($this->template);

        if (!$response) {
            $response = new Response();
            $e->setResponse($response);
        }
        $response->setStatusCode(403);
        $e->setResult($model);
    }
}
