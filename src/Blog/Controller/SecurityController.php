<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 10/17/14
 * Time: 12:41 PM
 */

namespace Blog\Controller;

use Blog\Model\User;
use Framework\Controller\Controller;
use Framework\Exception\DatabaseException;
use Framework\Response\ResponseRedirect;

class SecurityController extends Controller
{

    public function loginAction()
    {
        if ($this->getService('security')->isAuthenticated()) {
            return new ResponseRedirect($this->generateRoute('home'));
        }
        $errors = array();

        if ($this->getService('request')->isPost()) {

            if ($user = User::findByEmail($this->getService('request')->post('email'))) {
                if ($user->password == $this->getService('request')->post('password')) {
                    $this->getService('security')->setUser($user);
                    $returnUrl = $this->getService('session')->returnUrl;
                    unset($this->getService('session')->returnUrl);
                    return $this->redirect(!is_null($returnUrl)?$returnUrl:$this->generateRoute('home'));
                }
            }

            array_push($errors, 'Invalid username or password');
        }

        return $this->render('login.html', array('errors' => $errors));
    }

    public function logoutAction()
    {
        $this->getService('security')->clear();
        return $this->redirect($this->generateRoute('home'));
    }

    public function signinAction()
    {
        if ($this->getService('security')->isAuthenticated()) {
            return new ResponseRedirect($this->generateRoute('home'));
        }
        $errors = array();

        if ($this->getService('request')->isPost()) {
            try{
                $user           = new User();
                $user->email    = $this->getService('request')->post('email');
                $user->password = $this->getService('request')->post('password');
                $user->role     = 'ROLE_USER';
                $user->save();
                return $this->redirect($this->generateRoute('home'));
            } catch(DatabaseException $e){
                $errors = array($e->getMessage());
            }
        }

        return $this->render('signin.html', array('errors' => $errors));
    }
}