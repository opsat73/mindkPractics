<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 04.10.15
 * Time: 10:42
 */

namespace Framework\Validation\Filter;


class NotBlank implements IFIlter
{

    public function validateField($field) {
    if (($field == null) || (strlen($field) == 0))
    {
        return false;
    } else return true;
    }

    public function getErrorMessage()
    {
        $errormessage = 'can not be empty';
        return $errormessage;
    }
}