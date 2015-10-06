<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 04.10.15
 * Time: 10:08
 */

namespace Framework\Validation;


class Validator
{
    private $record;
    private $errors;
    public function __construct($record) {
        $this ->record = $record;
    }

    public function isValid() {
        $result = true;
        $class = get_class($this->record);
        $rules = $class::getRules();
        foreach ($rules as $field => $validators) {
            foreach ($validators as $key => $validator) {
                $tempResult = $validator->validateField($this->record->$field);
                if ($tempResult == false) {
                    $this->errors[$field] = $validator->getErrorMessage();
                    $result = false;
                }
            }
        }
        return $result;
    }

    public function getErrors() {
        return $this->errors;
    }


}