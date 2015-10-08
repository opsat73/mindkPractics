<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 08.10.15
 * Time: 22:03
 */

namespace Framework\Exception;


class AccessDenyException extends \Exception
{
    private $returnURL;
    public function __construct($returnURL) {
        parent::__construct('access deny');
        $this->returnURL = $returnURL;
    }

    public function getReturnURL() {
        return $this->returnURL;
    }
}