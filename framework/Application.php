<?php

/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 22.09.15
 * Time: 20:12
 */

namespace Framework;
use Framework\Controller\Controller;
use Framework\Exception\AccessDenyException;
use Framework\Exception\HttpNotFoundException;
use Framework\Exception\SecurityException;
use Framework\Renderer\Renderer;
use Framework\Response\Response;
use Framework\DI\Service;
use Framework\Response\ResponseRedirect;
use Framework\Router\Router;
use Framework\Security\Security;
use Framework\Session\SessionManager;

class Application
{

    private $config;
    private $result;

    /**
     * this class represent main class of application
     * @param $string String path to main config of application
     */
    public function __construct($string)
    {
        $this->config = include($string);
        $this->initApplication();
    }

    /**
     * main method of application
     * this method do next steps
     * 1) start session
     * 2) parse route
     * 3) render page
     * 4) send response to browser
     */
    public function run()
    {
        $router = Service::get('router');
        $router->routePath(Service::get('request')->getURN());
        $controller = $router->getController();
        $action = $router->getAction();
        $args = $router->getArgumetns();
        $grants = $router->getGrants();
        try {
            $security = new Security();
            if (!($security->isTokenCorrect())) {
                throw new SecurityException("bad request");
            }
            $this->result = Controller::executeAction($controller, $action, $args, $grants);
        } catch (AccessDenyException $e) {
            $session = Service::get('session');
            $session->addMessage('please login for doing this action', 'warning');
            $router = Service::get('router');
            $url = $router->getRoute('home');
            $this->result = new ResponseRedirect($url);
        } catch (\Exception $e) {
            $argsForRendering['message'] = $e->getMessage();
            $argsForRendering['code'] = 500;
            if ($e instanceof HttpNotFoundException)
                $argsForRendering['code'] = 404;
            $renderer = new Renderer;
            $this->result = new Response();
            $this->result->setContent($renderer->render($this->config['error_500'], $argsForRendering));
        }

        if ($this->result instanceof Response) {
            $renderer = new Renderer();
            $session = Service::get('session');
            $flush = $session->getFlush();
            $argsForRendering['content'] = $this->result->getContent();
            $argsForRendering['route'] = Service::get('router')->getRouteParams();
            $argsForRendering['user'] = Service::get('security') -> getUser();
            $argsForRendering['flush'] = $flush;
            $session->getAndClearFlush();
            $this->result->setContent($renderer->render($this->config['main_layout'], $argsForRendering));
        }
        $this->result->send();
    }

    private function initApplication() {
        $dbCredentials = $this->config['pdo'];
        $dns = "mysql:host=localhost;dbname=mindk;";

        Service::register('request', 'Framework\Request\Request');
        Service::register('db', '\PDO', array($dns, $dbCredentials['user'], $dbCredentials['password']));
        Service::register('response', 'Framework\Response\Response');
        Service::register('router', 'Framework\Router\Router', array($this->config['routes']));
        Service::register('security', 'Framework\Security\Security');
        Service::register('session', 'Framework\Session\SessionManager');
    }
}