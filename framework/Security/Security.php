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

class Security
{
    public function __construct() {
        $session = Service::get('session');
        $session -> startSession();
        $session->putParameter('isAuthenticated', false, false);
    }

    public function clear() {
        $session = Service::get('session');
        $session->putParameter('isAuthenticated', false);
        $session->removeParameter('userName');
    }

    public function isAuthenticated() {
        $session = Service::get('session');
        return $session->getParameter('isAuthenticated');

    }

    public function setUser($user) {
        $session = Service::get('session');
        $session -> putParameter('userName', $user->email);
        $session -> putParameter('isAuthenticated', true);
    }

    public function getUser()
    {
        $session = Service::get('session');
        if ($session->getParameter('isAuthenticated')) {
            $user = new User();
            $user->email = $session->getParameter('userName');
            return $user;
        }
    }



}