<?php

namespace App\Core;

use PDO;

class Database
{
    private static $instance = null;
    private $pdo;
    private $query;
    private $error=false;
    private $result;
    private $count=0;
    private $lastInsertID=null;

    private $select = ['*'];
    private $table;
    private $joins;
    private $where;
    private $params = [];
    private $distinct = false;
    private $select_top = false;
    private $order;
    private $groupBy;
    private $limit;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit();
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query($sql, $params = [],$class=false)
    {
        $this->error = false;
        if ($this->query = $this->pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->query->bindValue($x, $param);
                    $x++;
                }
            }
        }
        if ($this->query->execute()) {
            
            $this->result = ($class/* && $this->_fetchStyle === PDO::FETCH_CLASS*/) ? 
                $this->query->fetchAll(/*$this->_fetchStyle*/PDO::FETCH_CLASS, $class) : 
                $this->query->fetchAll(PDO::FETCH_OBJ);
            // echo "<pre>";
            // var_dump($this->result);echo "</pre>";exit();

            // $this->result = $this->query->fetchAll();
            $this->count = $this->query->rowCount();
            $this->lastInsertID = $this->pdo->lastInsertId();
        } else {
            // make an exception error
            // $this->query->errorInfo()
            $this->error = true;
        }

        return $this;
    }

    public function select(...$columns)
    {
        $this->select = [];
        foreach ($columns as $column) {
            $this->select[] = $column;
        }

        return $this;
    }

    public function table($table)
    {
        $this->table = getenv('DB_TABLE_PREFIX').$table;
        
        return $this;
    }

    public function join($joinType, $table, $comparison)
    {
        $this->joins = " {$joinType} {$table} ON {$comparison}";

        return $this;
    }
    
    public function innerJoin($table, $comparison)
    {
        $this->join('INNER JOIN', $table, $comparison);
        return $this;
    }

    public function outerJoin($table, $comparison)
    {
        $this->join('OUTER JOIN', $table, $comparison);
        return $this;
    }

    public function leftJoin($table, $comparison)
    {
        $this->join('LEFT JOIN', $table, $comparison);
        return $this;
    }

    public function rightJoin($table, $comparison)
    {
        $this->join('RIGHT JOIN', $table, $comparison);
        return $this;
    }

    public function joinAnd($statement)
    {
        $this->joins .= " AND {$statement}";
        return $this;
    }

    public function joinOr($statement)
    {
        $this->joins .= " OR {$statement}";

        return $this;
    }

    public function where($column, $value, $op = '=')
    {
        $this->where = " WHERE {$column} {$op} {$value}";
        $this->params[] = $value;
        return $this;
    }

    public function andWhere($column, $value, $op = '=')
    {
        $this->where .= " AND {$column} {$op} ?";
        $this->params[] = $value;
        return $this;
    }

    public function orWhere($column, $value, $op = '=')
    {
        $this->where .= " OR {$column} {$op} ?";
        $this->params[] = $value;
        return $this;
    }

    public function notWhere($column, $value, $op='=', $syntax='')
    {
        $this->where .= " {$syntax} NOT {$column} {$op} ?";
        $this->params[] = $value;
        return $this;
    }

    public function groupBy(...$columns)
    {
        foreach ($columns as $column) {
            $this->groupBy[] = $column;
        }
        return $this;
    }

    public function orderBy($column, $sort = 'ASC')
    {
        $this->order[$column] = $sort;
        return $this;
    }

    public function limit($rowCount, $offset = 0)
    {
        $this->limit = " LIMIT {$offset} , {$rowCount}";
        return $this;
    }

    public function get()
    {
        $sql = "SELECT";
        foreach ($this->select as $select) {
            $sql .= " {$select},";
        }
        $sql = rtrim($sql, ',');
        $sql .= " FROM {$this->table}";

        if ($this->joins) {
            $sql .= $this->joins;
        }

        if ($this->groupBy) {
            $sql .= " GROUP BY";
            foreach ($this->groupBy as $groupBy) {
                $sql .= " {$groupBy},";
            }
            $sql = rtrim($sql, ',');
        }

        if ($this->where) {
            $sql .= $this->where;
        }

        if ($this->order) {
            $sql .= " ORDER BY";
            foreach ($this->order as $col => $sort) {
                $sql .= " {$col} {$sort},";
            }
            $sql = rtrim($sql, ',');
        }

        if ($this->limit) {
            $sql .= $this->limit;
        }

        if (!$this->query($sql, $this->params)->error()) {
            return !empty($this->result) ? $this->result : [];
        }
        // return $this->query;
        return $this->error;
    }

    public function insert($params)
    {
        $fieldString = "";
        $valueString = "";
        $values = [];

        foreach ($params as $field => $value) {
            $fieldString .= "`{$field}`,";
            $valueString .= "?,";
            $values[] = $value;
        }

        $fieldString = rtrim($fieldString, ',');
        $valueString = rtrim($valueString, ',');
        $sql = "INSERT INTO {$this->table} ({$fieldString}) VALUES ({$valueString})";
        return !$this->query($sql, $values)->error();
    }

    public function update($params)
    {
        $fieldString = "";
        $values = [];
        foreach ($params as $field => $value) {
            $fieldString .= " {$field}=?,";
            $values[] = $value;
        }
        $fieldString = rtrim(trim($fieldString), ',');
        $sql = "UPDATE {$this->table} SET {$fieldString}{$this->where}";
        return !$this->query($sql, $values)->error();
    }

    public function delete()
    {
        $sql = "DELETE FROM {$this->table} {$this->where}";
        return !$this->query($sql)->error();
    }

    protected function read($class)
    {
        $sql = "SELECT";
        foreach ($this->select as $select) {
            $sql .= " {$select},";
        }
        $sql = rtrim($sql, ',');
        $sql .= " FROM {$this->table}";

        if ($this->joins) {
            $sql .= $this->joins;
        }

        if ($this->groupBy) {
            $sql .= " GROUP BY";
            foreach ($this->groupBy as $groupBy) {
                $sql .= " {$groupBy},";
            }
            $sql = rtrim($sql, ',');
        }

        if ($this->where) {
            $sql .= $this->where;
        }

        if ($this->order) {
            $sql .= " ORDER BY";
            foreach ($this->order as $col => $sort) {
                $sql .= " {$col} {$sort},";
            }
            $sql = rtrim($sql, ',');
        }

        if ($this->limit) {
            $sql .= $this->limit;
        }

        if ($this->query($sql, $this->params,$class)) {
            return count($this->result);
        }

        return false;
    }

    public function find($class=false)
    {
        if ($this->read($class)) {
            return $this->results();
        }
        return false;
    }
      
    public function findFirst($class=false)
    {
        if ($this->read($class)) {
            return $this->first();
        }
        return false;
    }

    public function error()
    {
        return $this->error;
    }

    public function results()
    {
        return $this->result;
    }

    public function first()
    {
        return !empty($this->result) ? $this->result[0] : [];
    }

    public function count()
    {
        return $this->count;
    }

    public function lastID()
    {
        return $this->lastInsertID;
    }

    public function get_columns($table)
    {
        return $this->query("SHOW COLUMNS FROM {$table}")->results();
    }
}
