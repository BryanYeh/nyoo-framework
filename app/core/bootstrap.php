<?php

function autoloader($namespace_class)
{
    $namespace = explode('\\', strtolower($namespace_class));
    $className = ucfirst(array_pop($namespace));
    $class = ROOT . DS . implode(DS, $namespace) . DS . $className . '.php';

    if (file_exists($class)) {
        require_once($class);
    }
}
spl_autoload_register('autoloader');
