<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 02.10.15
 * Time: 22:46
 */

namespace Framework\Session;


class SessionController
{

    public $returnUrl = null;

    public function __construct() {
    }

    public function hasParameter($parameter) {
        return array_key_exists($parameter, $_SESSION);
    }

    public function putParameter($parameter, $value, $rewrite = true) {
        if ($this -> hasParameter($parameter)) {
            if ($rewrite) {
                $_SESSION[$parameter] = $value;
            }
        } else {
            $_SESSION[$parameter] = $value;
        }
    }

    public function removeParameter ($parameter) {
        if ($this -> hasParameter($parameter))
            unset($_SESSION[$parameter]);
    }

    public function getParameter ($parameter) {
        if ($this->hasParameter($parameter))
            return $_SESSION[$parameter];
        return null;
    }

    public function startSession() {
        $status = session_status();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

}