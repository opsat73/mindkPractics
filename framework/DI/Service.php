<?php
/**
 * Created by PhpStorm.
 * User: viko0313
 * Date: 29.08.2015
 * Time: 0:43
 */

namespace Framework\DI;

/**
 * Class Service using for storing and gettin services in context of application
 * @package Framework\DI
 */
class Service
{
    private static $services = array();

    /**
     * register service in context of application
     * @param $key String key of services. this key use for getting service in future
     * @param $service String object or class name of services. If $service is name, service will be constructed if need
     * @param null $args arguments for constructing service
     */
    public static function register($key, $service, $args = null) {
        $needConstruct = !is_object($service);
        self::$services[$key] = new ServiceInstance($service, $needConstruct, $args);
    }

    /**
     * check existing of service
     * @param $key name of service
     * @return bool return true if service exist in application context
     */
    public static function hasService($key) {
        return (self::$services[$key] != null);
    }

    /**
     * method for getting service by name
     * @param $key String of service
     * @return mixed return service or null if service not exist
     */
    public static function get($key)
    {
        if (self::hasService($key))
            return self::$services[$key]->getService();
        return null;
    }
}

class ServiceInstance {
    private $isObject = false;
    private $service = null;
    private $argsForConstruct = null;

    public function __construct($service, $needConstructor, $args = null) {
        $this->isObject = !$needConstructor;
        $this->service = $service;
        $this->argsForConstruct = $args;
    }

    public function getService() {
        if ($this->isObject) {
            return $this->service;
        } else {
            $service = $this->service;
            $reflectionClass = new \ReflectionClass($service);
            if ($this->argsForConstruct != null) {
                $this->service = $reflectionClass->newInstanceArgs($this->argsForConstruct);
                $this->isObject = true;
                return $this->service;
            } else {
                $this->service = $reflectionClass->newInstanceArgs();
                $this->isObject = true;
                return $this->service;
            }
        }
    }
}