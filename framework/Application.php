<?php

/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 22.09.15
 * Time: 20:12
 */

namespace Framework;
use Framework\Controller\Controller;
use Framework\Exception\HttpNotFoundException;
use Framework\Exception\SecurityException;
use Framework\Renderer\Renderer;
use Framework\Response\Response;
use Framework\Router\Router;
use Framework\DI\Service;
use Framework\Security\Security;

class Application
{

    private $config;

    /**
     * this class represent main class of application
     * @param $string path to main config of application
     */
    public function __construct($string)
    {
        $this->config = include($string);
    }

    public function run()
    {
        $result = new Response();
        $argsForRendering = array();
        $dbCredentials = $this->config['pdo'];
        $dns = "mysql:host=localhost;dbname=mindk;";
        $db = new \PDO($dns, $dbCredentials['user'], $dbCredentials['password']);
        $router = new Router($this->config['routes']);

        Service::register('request', 'Framework\Request\Request', true);
        Service::register('db', $db, false);
        Service::register('response', 'Framework\Response\Response', true);
        Service::register('router', $router, false);
        Service::register('security', 'Framework\Security\Security', true);
        Service::register('session', 'Framework\Session\SessionManager', true);

        $session = Service::get('session');
        $session -> startSession();
        $router->routePath(Service::get('request')->getURN());
        $controller = $router->getController();
        $action = $router->getAction();
        $args = $router->getArgumetns();
        try {
            $security = new Security();
            if (!($security->isTokenCorrect())) {
                throw new SecurityException("bad request");
            }
            $result = Controller::executeAction($controller, $action, $args);
        } catch (\Exception $e) {
            $argsForRendering['message'] = $e->getMessage();
            $argsForRendering['code'] = 500;
            if ($e instanceof HttpNotFoundException)
                $argsForRendering['code'] = 404;
            $renderer = new Renderer;
            $result->setContent($renderer->render($this->config['error_500'], $argsForRendering));
        }

        if ($result instanceof Response) {
            $renderer = new Renderer();
            $session = Service::get('session');
            $flush = $session->getFlush();
            $argsForRendering['content'] = $result->getContent();
            $argsForRendering['route'] = Service::get('router')->getRouteParams();
            $argsForRendering['user'] = Service::get('security') -> getUser();
            $argsForRendering['flush'] = $flush;
            $session->getAndClearFlush();
            $result->setContent($renderer->render($this->config['main_layout'], $argsForRendering));
        }
        $result->send();
    }
}