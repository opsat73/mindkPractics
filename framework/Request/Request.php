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
        $this->urn = $_SERVER['PHP_SELF'];
        $this->uri = $this->url.$this->urn;
    }

    public function isPost() {
        return strtoupper($this->requestMethod) == "POST";
    }

    public function isGet() {
        return strtoupper($this->requestMethod) == "GET";
    }

    public function post($parameterName) {
        return $this->getParameter($parameterName);
    }

    public function get($parameterName) {
        return $this->getParameter($parameterName);
    }

    private function getParameter($parameterName) {
        if ($this->parameterExist($parameterName)) {
            $rawValue = $this->params[$parameterName];
            return $this->filterValue($rawValue);
        } else {
            /**
             * todo maybe throw exception or default value using mapping
             */
            return null;
        }
    }

    public function filterValue($value) {
        return preg_replace('/\s/', '', $value);
    }

    public function parameterExist($parameterName) {
        $result = array_key_exists($parameterName, $this->params);
        return $result;
    }

    public function getURL() {
        return $this->url;
    }

    public function getURN() {
        return $this->urn;
    }

    public function getURI() {
        return $this->uri;
    }
}