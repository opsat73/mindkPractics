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

    public function __construct($routes){
        $this->routes = $routes;
    }

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


        /**
         * todo need more args
         */
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

    public function getController() {
        return $this->controller;
    }

    public function getAction() {
       return $this->action;
    }

    public function getArgumetns() {
        return $this->arguments;
    }

    public function getRouteParams() {
        return $this->routeParams;
    }

    public function getRoute($route) {
        $rout_params = $this->routes[$route];
        return $rout_params['pattern'];
        /**
         * todo now without parameters
         */
    }
}