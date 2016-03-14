<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 02.10.15
 * Time: 23:05
 */

namespace Framework\Security;

use Blog\Model\User;
use Framework\DI\DIContainer;
use Framework\Exception\SecurityException;
use Framework\Exception\AccessDenyException;

class Security
{
    private $session = null;

    public function __construct()
    {
        $this->session = DIContainer::get('session');
        $this->session->putParameter('isAuthenticated', false, false);
    }

    /**
     * clear security parameters
     */
    public function clear()
    {
        $this->session->putParameter('isAuthenticated', false);
        $this->session->removeParameter('userName');
        $this->session->removeParameter('userRole');
    }

    /**
     * check if user authenticated
     *
     * @return mixed true if user authenticated
     */
    public function isAuthenticated()
    {
        return $this->session->getParameter('isAuthenticated');
    }

    /**
     * authenticate in session
     *
     * @param $user user for logining
     */
    public function setUser($user)
    {
        $this->session->putParameter('userName', $user->email);
        $this->session->putParameter('userRole', $user->getRole());
        $this->session->putParameter('isAuthenticated', true);
    }

    /**
     * return authenticated user from session
     *
     * @return User user from session
     */
    public function getUser()
    {
        if ($this->session->getParameter('isAuthenticated')) {
            $user        = new User();
            $user->email = $this->session->getParameter('userName');
            return $user;
        }
    }

    /**
     * generate random token using MD5 algorytm
     *
     * @return string token which was generated and setted in session
     */
    public function generateToken()
    {
        $token = md5(mktime());
        $this->session->putParameter('token', $token);
        return $token;
    }

    /**
     * check token
     *
     * @return bool return true if token correct
     */
    public function isTokenCorrect()
    {
        $request = DIContainer::get('request');
        $token   = null;
        if ($request->parameterExist('token')) {
            $token = $request->get('token');
        }

        if ($token != null) {
            $tokenFromSession = $this->session->getParameter('token');
            return $tokenFromSession == $token?true:false;
        } else {
            return true;
        }
    }

    /**
     * check token and throw SecurityException if token is incorrect
     */
    public function checkToken()
    {
        if (!$this->isTokenCorrect()) {
            throw new SecurityException('token is incorrect');
        }
    }

    public function checkGrants($grants = array())
    {
        $currentGrants = $this->session->getParameter('userRole');
        if (!in_array($currentGrants, $grants)) {
            $request   = DIContainer::get('request');
            $returnURL = $request->getURN();
            throw new AccessDenyException($returnURL);
        }
    }


}