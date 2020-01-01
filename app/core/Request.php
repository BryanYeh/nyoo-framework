<?php

namespace App\Core;

class Request
{
    private $method;
    private $query;
    private $path;
    private $params;
    private $queries;

    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->path = trim(strtolower($_SERVER['PATH_INFO'] ?? ''), '/');
        $this->query = strtolower($_SERVER['QUERY_STRING']);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    public function get($key)
    {
        return $this->params[$key];
    }

    public function setQuery($key, $value)
    {
        $this->queries[$key] = $value;
    }

    public function query($key)
    {
        return $this->queries[$key];
    }
}
