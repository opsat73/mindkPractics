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

    /**
     * @param $record record which need to validate
     */
    public function __construct($record) {
        $this ->record = $record;
    }

    /**
     * check record according rules and save errors if some fiels not valid
     * @return bool true if record valid according rules
     */
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

    /**
     * get errors
     * need call isValid() before
     * @return mixed array with errors
     */
    public function getErrors() {
        return $this->errors;
    }


}