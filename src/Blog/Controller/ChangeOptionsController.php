<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 10.10.15
 * Time: 17:19
 */

namespace Blog\Controller;

use Framework\Controller\Controller;

class ChangeOptionsController extends Controller
{
    public function setLocationAction($value, $returnURL)
    {
        $locatironManager = $this->getService('localization');
        $locatironManager->setLocale($value);
        $locatironManager->applyLocale();
        return $this->redirect($returnURL);
    }
}