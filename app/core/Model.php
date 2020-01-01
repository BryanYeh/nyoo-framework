<?php

namespace App\Core;

use App\Core\Database;

class Model
{
    protected $db;
    protected $table;
    protected $timestamp = false;
    public $id;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->db->table($this->table);
    }

    public function getColumns()
    {
        return $this->db->get_columns(getenv('DB_TABLE_PREFIX').$this->table);
    }

    public function getColumnsForSave()
    {
        $columns = $this->getColumns();
        $fields = [];
        foreach ($columns as $column) {
            $key = $column->Field;
            $fields[$key] = $this->{$key};
        }
        return $fields;
    }

    public function raw($sql, $params=[])
    {
        if (!$this->db->query($sql, $params, static::class)->error()) {
            return !empty($this->db->result) ? $this->db->result : [];
        }
        return $this->db->error;
    }

    public function save()
    {
        if($this->timestamp){
            $this->timeStamps();
        }
        $columns = $this->getColumnsForSave();
        if ($this->isNew()) {
            $save = $this->db->insert($columns);
            if ($save) {
                $this->id = $this->db->lastID();
            }
        } else {
            $save = $this->db->update($columns);
        }
        return $save;
    }

    public function timeStamps(){
        $dt = new \DateTime("now", new \DateTimeZone("UTC"));
        $now = $dt->format('Y-m-d H:i:s');
        $this->updated_at = $now;
        if($this->isNew()){
          $this->created_at = $now;
        }
      }

    public function select(...$columns)
    {
        $this->db->select($columns);
        return $this;
    }

    public function join($joinType, $table, $comparison)
    {
        $this->db->join($joinType, $table, $comparison);
        return $this;
    }

    public function innerJoin($table, $comparison)
    {
        $this->db->join('INNER JOIN', $table, $comparison);
        return $this;
    }

    public function outerJoin($table, $comparison)
    {
        $this->db->join('OUTER JOIN', $table, $comparison);
        return $this;
    }

    public function leftJoin($table, $comparison)
    {
        $this->db->join('LEFT JOIN', $table, $comparison);
        return $this;
    }

    public function rightJoin($table, $comparison)
    {
        $this->db->join('RIGHT JOIN', $table, $comparison);
        return $this;
    }

    public function joinAnd($statement)
    {
        $this->db->joinAnd($statement);
        return $this;
    }

    public function joinOr($statement)
    {
        $this->db->joinOr($statement);
        return $this;
    }

    public function where($column, $value, $op = '=')
    {
        $this->db->where($column, $value, $op);
        return $this;
    }

    public function andWhere($column, $value, $op = '=')
    {
        $this->db->andWhere($column, $value, $op);
        return $this;
    }

    public function orWhere($column, $value, $op = '=')
    {
        $this->db->orWhere($column, $value, $op);
        return $this;
    }

    public function notWhere($column, $value, $op='=', $syntax='')
    {
        $this->db->notWhere($column, $value, $op);
        return $this;
    }

    public function groupBy(...$columns)
    {
        $this->db->groupBy($columns);
        return $this;
    }

    public function orderBy($column, $sort = 'ASC')
    {
        $this->db->orderBy($column, $sort);
        return $this;
    }

    public function limit($rowCount, $offset = 0)
    {
        $this->db->limit($rowCount, $offset);
        return $this;
    }

    public function get()
    {
        return $this->db->find(static::class);
    }

    public function first()
    {
        return $this->db->findFirst(static::class);
    }



    protected function isNew()
    {
        return !(property_exists($this, 'id') && !empty($this->id));
    }
}
