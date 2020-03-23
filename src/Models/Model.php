<?php

namespace PHPDojo\Models;

use PHPDojo\Helpers\DatabaseConnection;

abstract class Model implements ModelFacade
{
    public abstract function save();

    protected abstract static function getCreateSql(): string;

    public abstract static function getTableName(): string;

    private $data;
//
//    public static function getTableName() {
//        $class_parts = explode('\\', get_called_class());
//        $withoutNamespace = end($class_parts);
//        return strtolower($withoutNamespace) . 's';
//    }

    protected final function setValueForKey($key, $value) {
        if ($this->data == null) {
            $this->data = array();
        }
        $this->data[$key] = $value;
    }

    /**
     * Adds a value to an array for the given key
     * @param $key
     * @param $value
     */
    protected final function addValueForKeyArray($key, $value) {
        if (!$this->isSet($key)) {
            throw new \Exception("This key is not set! " . $key);
        }
        if (!is_array($this->data[$key])) {
            throw new \Exception("This can only be used for array types!");
        }
        $this->data[$key][] = $value;
    }

    protected final function isSet($key) {
        return isset($this->data[$key]);
    }

    /**
     * Returns the value for the given key
     *
     * @param $key the key
     * @return |null the value as reference
     */
    protected final function getValueForKey($key) {
        if ($this->data != null && isset($this->data[$key])) {
            return $this->data[$key];
        }
        else {
            return null;
        }
    }

    public final function hasId() : bool {
        return $this->getId() != null;
    }

    public abstract function getId();

    public final static function createTable() {
        $mysqli = DatabaseConnection::mysqli();
        $mysqli->query(static::getCreateSql());
        if ($mysqli->error != null) {
            error_log($mysqli->error);
        }
    }
}