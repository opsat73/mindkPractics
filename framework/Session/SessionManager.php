<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 02.10.15
 * Time: 22:46
 */

namespace Framework\Session;


class SessionManager
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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function addMessage($message, $type = 'success') {
        $this->putParameter('flush', array(), false);
        $flush = $this->getParameter('flush');
        if (!array_key_exists($type, $flush)) {
            $flush[$type] = array();
        }
        array_push($flush[$type], $message);
        $this->putParameter('flush', $flush);
    }

    public function addErrorMessage($message) {
        $this->addMessage('error', $message);
    }

    public function getErrorMessage() {
        $this -> $this->getMessages('error');
    }

    public function clearErrorMessages() {
        $this->clearFlush('error');
    }

    public function getMessages($type)
    {
        $flush = $this->getParameter('flush');
        if (array_key_exists($type, $flush)) {
        return $flush[$type];
        } else
            return null;
    }

    public function clearFlush($type = null) {
        if ($type === null) {
            $this->putParameter('flush', array(), true);
        } else {
            $flush = $this->getParameter('flush');
            unset($flush[$type]);
        }
    }

    public function getMessagesAndClear($type) {
        $result = $this->getMessages($type);
        $this -> $this->clearFlush($type);
        return $result;
    }

    public function getFlush() {
        return $this->getParameter('flush');
    }

    public function getAndClearFlush() {
        $flush = $this->getFlush();
        $this->clearFlush();
        return $flush;
    }

}