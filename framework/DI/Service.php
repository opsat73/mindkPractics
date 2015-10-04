<?php
/**
 * Created by PhpStorm.
 * User: viko0313
 * Date: 29.08.2015
 * Time: 0:43
 */

namespace Framework\DI;

class Service
{

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null)
            self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        $this->register("request", "framework\\Request\\Request", true);
    }
    private static $services = array();

    public static function register($key, $service, $needConstruct) {
        self::$services[$key] = new ServiceInstance($service, $needConstruct);
    }

    public static function hasService($key) {
        return (self::$services[$key] != null);
    }

    public static function get($key) {
        return self::$services[$key]->getService();
    }
}

class ServiceInstance {
    private $isObject = false;
    private $service = null;

    public function __construct($service, $needConstructor) {
        $this->isObject = !$needConstructor;
        $this->service = $service;
    }

    public function getService() {
        if ($this->isObject) {
            return $this->service;
        } else {
            $service = $this->service;
            return  new $service;
        }
    }
}