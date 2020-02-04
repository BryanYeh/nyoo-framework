<?php

namespace App\Core;

use App\Core\Database;

class Migration
{
    protected $_db;
    protected $_table;
    protected $_sql;
    protected $_columns = [];

    public function __construct($table)
    {
        $this->_db = Database::getInstance();
        $this->_table = $table;
    }

    public function createTable()
    {
        $sql = "CREATE TABLE " . DB_TABLE_PREFIX . "{$this->_table} (" . implode(',',$this->_columns) . ")";
        return !$this->_db->query($sql)->error();
    }

    public function bigIncrements($name)
    {
        $this->_columns[] = "`{$name}` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function increments($name)
    {
        $this->_columns[] = "`{$name}` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }    

    public function mediumIncrements($name)
    {
        $this->_columns[] = "`{$name}` MEDIUMINT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function smallIncrements($name)
    {
        $this->_columns[] = "`{$name}` SMALLINT(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function bigInteger($name)
    {
        $this->_columns[] = "`{$name}` BIGINT(20)";
        return $this;
    }

    public function integer($name)
    {
        $this->_columns[] = "`{$name}` INTEGER(10)";
    }

    public function mediumInteger($name)
    {
        $this->_columns[] = "`{$name}` MEDIUMINT(8)";
        return $this;
    }

    public function smallInteger($name)
    {
        $this->_columns[] = "`{$name}` SMALLINT(5)";
        return $this;
    }

    public function tinyInteger($name)
    {
        $this->_columns[] = "`{$name}` TINYINT(4)";
        return $this;
    }

    public function boolean($name)
    {
        $this->_columns[] = "`{$name}` TINYINT(1)";
        return $this;
    }

    public function decimal($name, $precision, $digit)
    {
        $this->_columns[] = "`{$name}` DECIMAL({$precision},{$digit})";
        return $this;
    }

    public function float($name, $precision, $digit)
    {
        $this->_columns[] = "`{$name}` FLOAT({$precision},{$digit})";
        return $this;
    }

    public function double($name, $precision, $digit)
    {
        $this->_columns[] = "`{$name}` DOUBLE({$precision},{$digit})";
        return $this;
    }

    public function char($name, $length)
    {
        $this->_columns[] = "`{$name}` CHAR({$length})";
        return $this;
    }

    public function bit($name, $length)
    {
        $this->_columns[] = "`{$name}` BIT({$length})";
        return $this;
    }

    public function string($name, $length)
    {
        $this->_columns[] = "`{$name}` VARCHAR({$length})";
        return $this;
    }

    public function binary($name)
    {
        $this->_columns[] = "`{$name}` BINARY";
        return $this;
    }

    public function varBinary($name)
    {
        $this->_columns[] = "`{$name}` VARBINARY";
        return $this;
    }

    public function tinyBlob($name)
    {
        $this->_columns[] = "`{$name}` TINYBLOB";
        return $this;
    }

    public function blob($name)
    {
        $this->_columns[] = "`{$name}` BLOB";
        return $this;
    }

    public function mediumBlob($name)
    {
        $this->_columns[] = "`{$name}` MEDIUMBLOB";
        return $this;
    }

    public function longBlob($name)
    {
        $this->_columns[] = "`{$name}` LONGBLOB";
        return $this;
    }

    public function tinyText($name)
    {
        $this->_columns[] = "`{$name}` TINYTEXT";
        return $this;
    }

    public function text($name)
    {
        $this->_columns[] = "`{$name}` TEXT";
        return $this;
    }

    public function mediumText($name)
    {
        $this->_columns[] = "`{$name}` MEDIUMTEXT";
        return $this;
    }

    public function longText($name)
    {
        $this->_columns[] = "`{$name}` LONGTEXT";
        return $this;
    }

    public function set($name,$values)
    {
        $this->_columns[] = "`{$name}` SET(" . implode(',',$values) . ")";
        return $this;
    }    

    public function date($name)
    {
        $this->_columns[] = "`{$name}` DATE";
        return $this;
    }

    public function time($name)
    {
        $this->_columns[] = "`{$name}` TIME";
        return $this;
    }

    public function dateTime($name)
    {
        $this->_columns[] = "`{$name}` DATETIME";
        return $this;
    }

    public function timeStamp($name)
    {
        $this->_columns[] = "`{$name}` TIMESTAMP";
        return $this;
    }

    public function year($name)
    {
        $this->_columns[] = "`{$name}` YEAR";
        return $this;
    }

    public function geometry($name)
    {
        $this->_columns[] = "`{$name}` GEOMETRY";
        return $this;
    }

    public function point($name)
    {
        $this->_columns[] = "`{$name}` POINT";
        return $this;
    }

    public function lineString($name)
    {
        $this->_columns[] = "`{$name}` LINESTRING";
        return $this;
    }    

    public function polygon($name)
    {
        $this->_columns[] = "`{$name}` POLYGON";
        return $this;
    }

    public function geometrycollection($name)
    {
        $this->_columns[] = "`{$name}` GEOMETRYCOLLECTION";
        return $this;
    }

    public function multiLineString($name)
    {
        $this->_columns[] = "`{$name}` MULTILINESTRING";
        return $this;
    }

    public function multiPint($name)
    {
        $this->_columns[] = "`{$name}` MULTIPOINT";
        return $this;
    }

    public function multiPolygon($name)
    {
        $this->_columns[] = "`{$name}` MULTIPOLYGON";
        return $this;
    }

    public function json($name)
    {
        $this->_columns[] = "`{$name}` JSON";
        return $this;
    }

    public function after($column)
    {
        $this->_columns[] = array_pop($this->_columns) . " AFTER `{$column}`";
        return $this;
    }

    public function autoIncrement()
    {
        $this->_columns[] = array_pop($this->_columns) . " AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }
}
