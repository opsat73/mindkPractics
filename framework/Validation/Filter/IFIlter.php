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
    public function validateField($field);
    public function getErrorMessage();
}