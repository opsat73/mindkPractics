<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 04.10.15
 * Time: 10:33
 */

namespace Framework\Validation\Filter;


class Length implements IFIlter
{
    private $minLength;
    private $maxLength;
    public function __construct($minLength, $maxLength) {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }

    public function validateField($field) {
        if ((strlen($field) >= $this->minLength) && (strlen($field) <= $this ->maxLength)) {
            return true;
        } else return false;
    }

    public function getErrorMessage()
    {
        $errormessage = ' length need to be between '. $this->minLength. ' and '. $this->maxLength.' symbols';
        return $errormessage;
    }
}