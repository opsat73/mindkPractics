<?php

/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 29.09.15
 * Time: 20:22
 */

namespace Framework\Renderer;

use Framework\DI\Service;

class Renderer
{
    public function render($templateFile, $params) {
        $includeFunc = function ($controller, $action, $args = array()) {
            $controllerInstance = new $controller();
            if ($args === null)
                $args = array();
            return call_user_func_array(array($controllerInstance,$action.'Action') , $args);
        };

        $getRouteFunc = function($rout) {
            $router = Service::get('router');
            return $router->getRoute($rout);
        };

        $params['include'] = $includeFunc;
        $params['getRoute'] = $getRouteFunc;

        ob_start();
        extract($params);
        include($templateFile);
        return ob_get_clean();
    }
}
