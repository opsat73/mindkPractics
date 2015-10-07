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

    /**
     * method for execution action of controller. Can return some type of response
     * @param $controller controller name
     * @param $action action name
     * @param array $args arguments for action
     * @return mixed Response or Redirect Response
     */
    public static function executeAction($controller, $action, $args = array()) {
        $controllerInstance = new $controller();
        if ($args === null)
            $args = array();
        return call_user_func_array(array($controllerInstance,$action) , $args);
    }

    /**
     * this method render template using renderer and insert result in Response with response code 200
     * @param $template name of template which need be in folder with name of Controller
     * @param array $params parameters  for rendering
     * @return Response with code 200
     */
    public function render($template, $params = array()) {
        $callerClassName = \get_called_class();
        $callerNameSpaces = \explode('\\', $callerClassName);
        $templateFolder = __DIR__.'/../../src/Blog/views/'.str_replace("Controller", '', \end($callerNameSpaces));
        $templateFile = $templateFolder.'/'.$template.'.php';

        $renderer = new Renderer();
        $resp = new Response($renderer->render($templateFile, $params));
        return $resp;
    }

    /**
     * @return mixed Request object from application context
     */
    public function getRequest() {
        return Service::get('request');
    }

    /**
     * return route path using config files
     * @param $rout name of rout
     * @return mixed route path
     */
    public function generateRoute($rout) {
        $router = Service::get('router');
        return $router->getRoute($rout);
    }

    /**
     * method using for get Redirect Response
     * @param $redirectTo path for redirecting
     * @param $message $message redirect message and setting this message in Flush of session. type of message will be "success"
     * @return ResponseRedirect
     */
    public function redirect($redirectTo, $message = null) {
        if ($message != null) {
            $session = Service::get('session');
            $session ->addMessage($message);
        }
        return new ResponseRedirect($redirectTo);
    }
}