<?php

/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 22.09.15
 * Time: 20:12
 */

namespace Framework;
use Framework\Request\Request;
use Framework\Router\Router;

class Application
{

    /**
     * Application constructor.
     * @param string $string
     */
    private $config;

    /**
     * this class represent main class of application
     * @param $string path to main config of application
     */
    public function __construct($string)
    {
        $this->config = include($string);
        print_r($this->config);
    }

    public function run()
    {
        $request = new Request();
        $path = $request->getURN();
        $router = new Router($this->config['routes']);
        $router->routePath($path);
    }
}