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

    /**
     * construcn session manager and start session
     */
    public function __construct() {
        $this->startSession();
    }

    /**
     * check if parameter exist in Session context
     * @param $parameter String name of parameter
     * @return bool true if parameter exist
     */
    public function hasParameter($parameter) {
        return array_key_exists($parameter, $_SESSION);
    }

    /**
     * put parameter with value in SESSION context
     * @param $parameter String name of parameter
     * @param $value value of parameter
     * @param bool|true $rewrite if need rewrite parameter in session context, use TRUE
     */
    public function putParameter($parameter, $value, $rewrite = true) {
        if ($this -> hasParameter($parameter)) {
            if ($rewrite) {
                $_SESSION[$parameter] = $value;
            }
        } else {
            $_SESSION[$parameter] = $value;
        }
    }

    /**
     * remove parameter from SESSION context
     * @param $parameter String name of parameter
     */
    public function removeParameter ($parameter) {
        if ($this -> hasParameter($parameter))
            unset($_SESSION[$parameter]);
    }

    /**
     * get parameter by name form SESSION context
     * @param $parameter String name of parameter
     * @return value or noll if parameter not exist
     */
    public function getParameter ($parameter) {
        if ($this->hasParameter($parameter))
            return $_SESSION[$parameter];
        return null;
    }

    private function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->putParameter('flush', array(), false);
    }

    /**
     * stop session if not need anymore
     */
    public function stopSession() {
        if (session_status() == PHP_SESSION_DISABLED) {
            session_abort();
        }
    }

    /**
     * method add message in session context
     * @param $message String message for adding
     * @param string $type type of message. default is "success"
     */
    public function addMessage($message, $type = 'success') {
        $flush = $this->getParameter('flush');
        if (!array_key_exists($type, $flush)) {
            $flush[$type] = array();
        }
        array_push($flush[$type], $message);
        $this->putParameter('flush', $flush);
    }

    /**
     * add message with type "error" in session context
     * @param $message error message
     */
    public function addErrorMessage($message) {
        $this->addMessage('error', $message);
    }

    /**
     * return array with error messages
     */
    public function getErrorMessage() {
        $this -> $this->getMessages('error');
    }

    /**
     * clear messages with type "error"
     */
    public function clearErrorMessages() {
        $this->clearFlush('error');
    }

    /**
     * @param $type type of message
     * @return array of messages by type or null if messages by type not exist
     */
    public function getMessages($type)
    {
        $flush = $this->getParameter('flush');
        if (array_key_exists($type, $flush)) {
        return $flush[$type];
        } else
            return null;
    }

    /**
     * clear all messages in Session context by type. if type is null or arguments not exist clear all session
     * @param null $type type of messages
     */
    public function clearFlush($type = null) {
        if ($type === null) {
            $this->putParameter('flush', array(), true);
        } else {
            $flush = $this->getParameter('flush');
            unset($flush[$type]);
        }
    }

    /**
     * get messages by type and clear from SESSION context
     * @param $type type of messages
     * @return array array with messages or null if messages not exist in SESSION context
     */
    public function getMessagesAndClear($type) {
        $result = $this->getMessages($type);
        $this -> $this->clearFlush($type);
        return $result;
    }

    /**
     * get all messages
     * @return value array with messages from Session context
     */
    public function getFlush() {
        return $this->getParameter('flush');
    }

    /**
     * get and clear all messages
     * @return value array with messages from SESSION context
     */
    public function getAndClearFlush() {
        $flush = $this->getFlush();
        $this->clearFlush();
        return $flush;
    }

}