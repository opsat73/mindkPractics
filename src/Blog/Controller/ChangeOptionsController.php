<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 10.10.15
 * Time: 17:19
 */

namespace Blog\Controller;


use Framework\Controller\Controller;
use Framework\DI\Service;

class ChangeOptionsController extends Controller
{
    public function setLocationAction($value, $returnURL) {
        $locatironManager = Service::get('localization');
        $locatironManager->setLocale($value);
        $locatironManager->applyLocale();
        return $this->redirect($returnURL);
    }
}