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
    public $id;

    public abstract static function getTable();

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

    protected function getFieldValues() {
        $reflect = new \ReflectionClass($this);
        $fields = $reflect ->getProperties();
        $values = array();
        for ($i = 0; $i < count($fields); $i++) {
            $values[$fields[$i]->getName()] = $fields[$i]->getValue($this);
        }
        return $values;
    }

    public static function find($parameter) {
        if ($parameter === 'all') {
            return self::findByField(null, null, null);
        } else {
            return self::findByField('id', $parameter, true);
        }
    }

    public static function findByField($fieldName, $keyValue, $needFirstOnly = false) {
        $condition = null;
        if ($fieldName === null) {
            $condition = "where id = ? limit 1";
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

    public function delete() {
        $statement = Service::get('db')->prepare("delete from ".static::getTable()." where id = ?");
        $key = $this->getFieldValues();
        $key = $key['id'];
        if ($key != null) {
            $statement->execute(array($key));
        }
    }

    public function getRules() {
        return null;
    }

}