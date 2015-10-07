<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 06.10.15
 * Time: 22:03
 */

namespace Framework\Validation\Filter;


interface IFIlter
{
    /**
     * @param $field name of field for validating
     * @return bool true if validation success and false if validation failed
     */
    public function validateField($field);

    /**
     * @return String error message if validation failed
     */
    public function getErrorMessage();
}