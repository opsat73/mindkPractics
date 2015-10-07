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

    /**
     * validator for validating length of field
     * @param $minLength min length of field
     * @param $maxLength max length of field
     */
    public function __construct($minLength, $maxLength) {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }

    /**
     * validate field for length
     * @param name $field field for validating
     * @return bool valitation result
     */
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