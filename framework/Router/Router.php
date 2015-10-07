<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 22.09.15
 * Time: 20:16
 */

namespace Framework\Router;

class Router
{
    private $routes;
    private $controller;
    private $action;
    private $arguments = array();
    private $routeParams;

    /**
     * construct object for routing
     * @param $routes array with mapping of routes
     */
    public function __construct($routes){
        $this->routes = $routes;
    }

    /**
     * method parse path and parse controller name, action and arguments according routing mapping
     * @param $path urn address for parsing
     */
    public function routePath($path) {
        $controller = null;
        $action = null;
        $rez = array();

        foreach ( $this->routes as $key => $rout){
            $pattern = $this->getRegexpByRout($rout);
            preg_match($pattern, $path,$rez);
            if ((!empty($rez))&&($path == $rez[0])) {
                $controller = '\\'.$rout['controller'];
                $action = $rout['action'];
                $action = $action.'Action';
                $this->routeParams['_name'] = $key;

                break;
            }
        }

        $argsArray = array();

        foreach($rez as $key=>$value) {
            if ($key != 0) {
                $argsArray[$key] = $value;
            }
        }

            $this->controller = $controller;
            $this->action = $action;
            $this->arguments = $argsArray;
    }

    private function getRegexpByRout($rout) {
        $pattern = $rout['pattern'];

        $pattern = preg_replace('/\//', '\/', $pattern);

        preg_match_all('/{(.*)}/U' ,$pattern, $placeholders);
        $placeholdersCount = count($placeholders[0]);
        for ($i = 0; $i < $placeholdersCount; $i++) {
            $changeTo = $rout['_requirements'][$placeholders[1][$i]];
            $change = $placeholders[0][$i];
            $pattern = preg_replace('/'.$change.'/', '('.$changeTo.')', $pattern);
        }
        return '/'.$pattern.'/';
    }

    /**
     * return name of controller
     * routePath() need be executed before
     * @return mixed controlle name
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * return name of action
     * routePath() need be executed before
     * @return mixed action name
     */
    public function getAction() {
       return $this->action;
    }

    /**
     * return arguments
     * routePath() need be executed before
     * @return array arguments for action
     */
    public function getArgumetns() {
        return $this->arguments;
    }

    /**
     * return array with parameters of parsed rout
     * keys:
     * _name = name of rout from mapping
     * @return mixed array with parameters
     */
    public function getRouteParams() {
        return $this->routeParams;
    }

    /**
     * method construct route using mapping
     * @param $route name of route
     * @return mixed return route by key according route mapping
     */
    public function getRoute($route) {
        $rout_params = $this->routes[$route];
        return $rout_params['pattern'];
    }
}