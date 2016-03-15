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
 *
 * @package Framework\DI
 */
class DIContainer
{
    const SERVICE_BUILT = 2;
    const SERVICE_AVAILABE = 1;
    const SERVICE_NOT_FOUND = null;
    private static $services = array();
    private static $available_services = array();

    /**
     * init DI container
     */

    public static function  init($config_file)
    {
        self::$available_services = include($config_file);
    }

    /**
     * check existing of service
     *
     * @param $key name of service
     *
     * @return bool return true if service exist in application context
     */
    public static function hasService($key)
    {
        if (self::$services[$key] != null) {
            return self::SERVICE_BUILT;
        }
        if (self::$available_services[$key] != null) {
            return self::SERVICE_AVAILABE;
        }
        return self::SERVICE_NOT_FOUND;
    }

    /**
     * method for getting service by name
     *
     * @param $key String of service
     *
     * @return mixed return service or null if service not exist
     */
    public static function get($key)
    {
        if (self::hasService($key) == self::SERVICE_BUILT) {
            return self::$services[$key];
        }
        if (self::hasService($key) == self::SERVICE_AVAILABE) {
            self::$services[$key] = self::buildService($key);
            return self::$services[$key];
        }
        return self::SERVICE_NOT_FOUND;
    }

    private static function buildService($key)
    {
        $class           = self::$available_services[$key]['class'];
        $reflectionClass = new \ReflectionClass($class);
        $parameters      = array();
        if (isset(self::$available_services[$key]['init_parameters'])) {
            $parameters = self::$available_services[$key]['init_parameters'];
        }
        if (isset(self::$available_services[$key]['config_file'])) {
            $parameters = include(self::$available_services[$key]['config_file']);
        }
        $newInstance = $reflectionClass->newInstanceArgs($parameters);
        return $newInstance;
    }
}