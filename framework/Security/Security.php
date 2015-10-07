<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 02.10.15
 * Time: 23:05
 */

namespace Framework\Security;


use Blog\Model\User;
use Framework\DI\Service;
use Framework\Exception\SecurityException;
use Framework\Request\Request;

class Security
{
    public function __construct() {
        $session = Service::get('session');
        $session -> startSession();
        $session->putParameter('isAuthenticated', false, false);
    }

    /**
     * clear security parameters
     */
    public function clear() {
        $session = Service::get('session');
        $session->putParameter('isAuthenticated', false);
        $session->removeParameter('userName');
    }

    /**
     * check if user authenticated
     * @return mixed true if user authenticated
     */
    public function isAuthenticated() {
        $session = Service::get('session');
        return $session->getParameter('isAuthenticated');

    }

    /**
     * authenticate in session
     * @param $user user for logining
     */
    public function setUser($user) {
        $session = Service::get('session');
        $session -> putParameter('userName', $user->email);
        $session -> putParameter('isAuthenticated', true);
    }

    /**
     * return authenticated user from session
     * @return User user from session
     */
    public function getUser()
    {
        $session = Service::get('session');
        if ($session->getParameter('isAuthenticated')) {
            $user = new User();
            $user->email = $session->getParameter('userName');
            return $user;
        }
    }

    /**
     * generate random token using MD5 algorytm
     * @return string token which was generated and setted in session
     */
    public function generateToken() {
        $session = Service::get('session');
        $token = md5(mktime());
        $session -> putParameter('token', $token);
        return $token;
    }

    /**
     * check token
     * @return bool return true if token correct
     */
    public function isTokenCorrect() {
        $request = Service::get('request');
        $token = null;
        if ($request->parameterExist('token')) {
            $token = $request->get('token');
        }

        if ($token != null) {
            $tokenFromSession = Service::get('session')->getParameter('token');
            return $tokenFromSession == $token? true: false;
        } else {
            return true;
        }
    }

    /**
     * check token and throw SecurityException if token is incorrect
     */
    public function checkToken () {
        if (!$this->isTokenCorrect())
            throw new SecurityException('token is incorrect');
    }



}