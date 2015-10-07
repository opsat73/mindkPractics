<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 22.09.15
 * Time: 23:21
 */

namespace Framework\Request;


class Request
{
    private $params;
    private $requestMethod;
    private $url;
    private $urn;
    private $uri;

    public function __construct() {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $this->params = $_REQUEST;
        $this->url = $_SERVER['HTTP_HOST'];
        $this->urn = $_SERVER['REQUEST_URI'];
        $this->uri = $this->url.$this->urn;
    }
    /**
     * check request for POST type
     * @return bool true if request type is POST and false if another
     */
    public function isPost() {
        return strtoupper($this->requestMethod) == "POST";
    }

    /**
     * check request for GET type
     * @return bool true if request type is GET and false if another
     */
    public function isGet() {
        return strtoupper($this->requestMethod) == "GET";
    }

    /**
     * get parameter from request by name
     * @param $parameterName name of parameter
     * @return mixed|null return value of parameter or null if parameter not exist
     */
    public function post($parameterName) {
        return $this->getParameter($parameterName);
    }

    /**
     * get parameter from request by name
     * @param $parameterName name of parameter
     * @return mixed|null return value of parameter or null if parameter not exist
     */
    public function get($parameterName) {
        return $this->getParameter($parameterName);
    }

    private function getParameter($parameterName) {
        if ($this->parameterExist($parameterName)) {
            $rawValue = $this->params[$parameterName];
            return $this->filterValue($rawValue);
        } else {
            return null;
        }
    }


    /**
     * filter falue from not typed chars
     * @param $value value which need to filter
     * @return mixed filtered value
     */
    public function filterValue($value) {
        return preg_replace('/[\n\r\t]/', '', $value);
    }

    /**
     * check if parameter exist in request
     * @param $parameterName name of parameter
     * @return bool true if parameter exist
     */
    public function parameterExist($parameterName) {
        $result = array_key_exists($parameterName, $this->params);
        return $result;
    }

    /**
     * @return mixed url of request
     */
    public function getURL() {
        return $this->url;
    }

    /**
     * @return mixed return URN of request
     */
    public function getURN() {
        return $this->urn;
    }

    /**
     * @return string return URI from request
     */
    public function getURI() {
        return $this->uri;
    }
}