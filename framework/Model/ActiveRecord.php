<?php

/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 29.09.15
 * Time: 20:12
 */

namespace Framework\Model;

use Framework\DI\Service;

abstract class ActiveRecord
{
    /**
     * @var default key field
     */
    public $id;

    /**
     * @return mixed name of table
     */
    public abstract static function getTable();

    /**
     * @return string return name of key field
     */
    public static function getKeyField() {
        return 'id';
    }

    /**
     * save record in Data Base
     */
    public function save() {
        $needToSave = $this->getFieldValues();
        $columnQueue = "";
        $valueQueue ="";

        foreach ($needToSave as $key => $value) {
            $columnQueue = $columnQueue.$key.", ";
            $valueQueue = $valueQueue.'"'.$value.'", ';
        }
        $columnQueue = substr($columnQueue, 0, strlen($columnQueue)-2);
        $valueQueue = substr($valueQueue, 0, strlen($valueQueue)-2);
        $query = "replace into ".static::getTable(). "( ".$columnQueue.") values ( ".$valueQueue.")";
        Service::get('db')->beginTransaction();
        Service::get('db')->query($query);
        Service::get('db')->commit();
    }

    /**
     * @return array array with names of fields in record
     */
    protected function getFieldNames() {
        $reflect = new \ReflectionClass($this);
        $fields = $reflect->getProperties();
        $fieldsName = array();
        for ($i = 0; $i < count($fields); $i++) {
            if ($fields[$i]->getName() != '$DBMconnect')
                $fieldsName[$i] = $fields[$i]->getName();
        }
        return $fieldsName;
    }

    /**
     * @return array return array with values of field
     */
    protected function getFieldValues() {
        $reflect = new \ReflectionClass($this);
        $fields = $reflect ->getProperties();
        $values = array();
        for ($i = 0; $i < count($fields); $i++) {
            $values[$fields[$i]->getName()] = $fields[$i]->getValue($this);
        }
        return $values;
    }

    /**
     * @param $parameter 'all' if need find all records in table and key value if need to find record by key field
     * @return array if need find all record or one record if use finding by key field
     */
    public static function find($parameter) {
        if ($parameter === 'all') {
            return self::findByField(null, null, null);
        } else {
            return self::findByField(static::getKeyField(), $parameter, true);
        }
    }

    /**
     * method use fo find record by field
     * @param $fieldName field name which use for finding. if null use key field
     * @param $keyValue value for finding
     * @param bool|false $needFirstOnly if ture, return one record, if false, return array with records
     * @return array result of finding with record if one and array of record needFirsOnly = false
     */
    public static function findByField($fieldName, $keyValue, $needFirstOnly = false) {
        $condition = null;
        if ($fieldName === null) {
            $condition = "where ".self::getKeyField()." = ? limit 1";
        } else {
            $condition = "where " . $fieldName . " = ?";
            if ($needFirstOnly)
                $condition = $condition." limit 1";
        }

        if ($keyValue === null)
            $condition = null;
        $selectQuery = "select * from ".static::getTable()." ". $condition;
        $statement = Service::get('db')->prepare($selectQuery);
        $result = $statement->execute(array($keyValue));
        $records = array();
        $recordsCount = 0;
        while ($row = $statement->fetch()) {
            $object = new static();
            foreach ($object->getFieldNames() as $key) {
                $object->{$key} = $row[$key];
            }
            $records[$recordsCount++] = $object;
        }
        if (array_key_exists(0, $records)) {
            if ($needFirstOnly)
                $records = $records[0];
            return $records;
        }
    }

    /**
     * delete recod from data base
     */
    public function delete() {
        $statement = Service::get('db')->prepare("delete from ".static::getTable()." where id = ?");
        $key = $this->getFieldValues();
        $key = $key['id'];
        if ($key != null) {
            $statement->execute(array($key));
        }
    }
    /**
     * @return rules for validating field
     */
    public function getRules() {
        return null;
    }

}