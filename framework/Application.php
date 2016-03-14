<?php

/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 22.09.15
 * Time: 20:12
 */

namespace Framework;

use Framework\Controller\Controller;
use Framework\Exception\AccessDenyException;
use Framework\Exception\HttpNotFoundException;
use Framework\Exception\SecurityException;
use Framework\Renderer\Renderer;
use Framework\Response\Response;
use Framework\DI\DIContainer;
use Framework\Response\ResponseRedirect;
use Framework\Security\Security;

class Application
{

    private $config;
    private $result;

    /**
     * this class represent main class of application
     *
     * @param $string String path to main config of application
     */
    public function __construct($string)
    {
        $this->config = include($string);
        DIContainer::init($this->config[services]);
    }

    /**
     * main method of application
     * this method do next steps
     * 1) apply localization
     * 2) parse route
     * 3) render page
     * 4) send response to browser
     */
    public function run()
    {
        $localization = $this->get('localization');
        $localization->applyLocale();

        $router  = $this->get('router');
        $request = $this->get('request');
        $router->routePath($request->getURN());
        $controller = $router->getController();
        $action     = $router->getAction();
        $args       = $router->getArgumetns();
        $grants     = $router->getGrants();
        try{
            $security = new Security();
            if (!($security->isTokenCorrect())) {
                throw new SecurityException("bad request");
            }
            $this->result = Controller::executeAction($controller, $action, $args, $grants);
        } catch(AccessDenyException $e){
            $request = $this->get('request');
            $session = $this->get('session');
            $session->setReturnURL($request->getURN());
            $url          = $router->getRoute('login');
            $this->result = new ResponseRedirect($url);
        } catch(\Exception $e){
            $argsForRendering['message'] = $e->getMessage();
            $argsForRendering['code']    = 500;
            if ($e instanceof HttpNotFoundException) {
                $argsForRendering['code'] = 404;
            }
            $renderer     = new Renderer;
            $this->result = new Response();
            $this->result->setContent($renderer->render($this->config['error_500'], $argsForRendering));
        }

        if ($this->result instanceof Response) {
            $renderer                       = new Renderer();
            $session                        = $this->get('session');
            $flush                          = $session->getFlush();
            $argsForRendering['curLocale']  = $localization->getCurrentLocale();
            $argsForRendering['avalLocale'] = $localization->getAvailableLocales();
            $argsForRendering['currentURN'] = $request->getURN();
            $argsForRendering['content']    = $this->result->getContent();
            $argsForRendering['route']      = $this->get('router')->getRouteParams();
            $argsForRendering['user']       = $this->get('security')->getUser();
            $argsForRendering['flush']      = $flush;
            $session->getAndClearFlush();
            $this->result->setContent($renderer->render($this->config['main_layout'], $argsForRendering));
        }
        $this->result->send();
    }

    private function get($service)
    {
        return DIContainer::get($service);
    }
}