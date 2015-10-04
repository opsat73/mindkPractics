<?php

/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 22.09.15
 * Time: 20:12
 */

namespace Framework;
use Framework\Controller\Controller;
use Framework\Renderer\Renderer;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Router\Router;
use Framework\DI\Service;

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
        $dns = "mysql:host=localhost;dbname=mindk;";
        $db = new \PDO($dns, "root", "RE3r9D+z");
        $router = new Router($this->config['routes']);
        /**
         * todo use config files
         */

        Service::register('request', 'Framework\Request\Request', true);
        Service::register('db', $db, false);
        Service::register('response', 'Framework\Response\Response', true);
        Service::register('router', $router, false);
        Service::register('security', 'Framework\Security\Security', true);
        Service::register('session', 'Framework\Session\SessionController', true);


        $router->routePath(Service::get('request')->getURN());
        $controller = $router->getController();
        $action = $router->getAction();
        $args = $router->getArgumetns();
        $result = Controller::executeAction($controller, $action, $args);

        if ($result instanceof Response) {
            $renderer = new Renderer();
            $argsForRendering = array();
            $argsForRendering['content'] = $result->getContent();
            $argsForRendering['route'] = Service::get('router')->getRouteParams();
            $argsForRendering['user'] = Service::get('security') -> getUser();
            $result->setContent($renderer->render($this->config['main_layout'], $argsForRendering));
        }
        $result->send();
    }
}