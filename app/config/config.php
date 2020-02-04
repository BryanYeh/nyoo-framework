<?php

define('ENVIRONMENT',getenv('APP_MODE'));
define('THEME',getenv('THEME'));

// Database
define('DB_USER',getenv('DB_USER'));
define('DB_PASS',getenv('DB_PASSWORD'));
define('DB_DSN','mysql:dbname=' . getenv('DB_DATABASE') . ';host=' . getenv('DB_HOST'));
define('DB_TABLE_PREFIX',getenv('DB_TABLE_PREFIX'));

define('SESSION_NAME',getenv('SESSION_NAME'));
define('COOKIE_NAME',getenv('COOKIE_NAME'));

define('ERROR_LOG', ROOT . DS . 'app' . DS . 'storage' . DS . 'logs' . DS . 'error.log' );
define('EXCEPTION_LOG', ROOT . DS . 'app' . DS . 'storage' . DS . 'logs' . DS . 'exception.log' );