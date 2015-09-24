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

    public function __construct($routes){
        $this->routes = $routes;
    }

    public function routePath($path) {
        $controller = null;
        $action = null;

        foreach ( $this->routes as $rout){
            $pattern = $this->getRegexpByRout($rout);
            preg_match($pattern, $path,$rez);
            if ($path == $rez[0]) {
                $controller = '\\'.$rout['controller'];
                $action = $rout['action'];
                $action = $action.'Action';
                break;
            }
        }

        if (class_exists($controller)) {
            $controller = new $controller();
            if (method_exists($controller, $action))
                $controller->$action();
        }
    }

    private function getRegexpByRout($rout) {
        $pattern = $rout['pattern'];

        $pattern = preg_replace('/\//', '\/', $pattern);

        preg_match_all('/{(.*)}/U' ,$pattern, $placeholders);
        $placeholdersCount = count($placeholders[0]);
        for ($i = 0; $i < $placeholdersCount; $i++) {
            $changeTo = $rout['_requirements'][$placeholders[1][$i]];
            $change = $placeholders[0][$i];
            $pattern = preg_replace('/'.$change.'/', $changeTo, $pattern);
        }
        return '/'.$pattern.'/';
    }

    /**
     * todo generateRoute
     */
}