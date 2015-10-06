<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 29.09.15
 * Time: 19:07
 */

namespace Framework\Controller;


use Framework\DI\Service;
use Framework\Renderer\Renderer;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\Session\SessionManager;

class Controller
{

    public static function executeAction($controller, $action, $args = array()) {
        $controllerInstance = new $controller();
        if ($args === null)
            $args = array();
        return call_user_func_array(array($controllerInstance,$action) , $args);
    }

    public function render($template, $params = array()) {
        $callerClassName = \get_called_class();
        $callerNameSpaces = \explode('\\', $callerClassName);
        $templateFolder = __DIR__.'/../../src/Blog/views/'.str_replace("Controller", '', \end($callerNameSpaces));
        $templateFile = $templateFolder.'/'.$template.'.php';

        $renderer = new Renderer();
        $resp = new Response($renderer->render($templateFile, $params));
        return $resp;
    }

    public function getRequest() {
        return Service::get('request');
    }

    public function generateRoute($rout) {
        $router = Service::get('router');
        return $router->getRoute($rout);
    }

    public function redirect($redirectTo, $message = null) {
        if ($message != null) {
            $session = Service::get('session');
            $session ->addMessage($message);
        }
        return new ResponseRedirect($redirectTo);
    }



}