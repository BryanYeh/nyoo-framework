<?php
use App\Core\Router;
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use App\Core\Error;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use App\Core\View;

define('DS', DIRECTORY_SEPARATOR); // either / or \ depending on OS
define('ROOT', dirname(__DIR__)); // C:\xampp\htdocs\framework

// bootstrap file
require_once(ROOT. DS . 'app' . DS . 'core' . DS . 'bootstrap.php');

// vendor autoload file
require_once(ROOT . DS . 'vendor' . DS . 'autoload.php');

// Need to have this in order to use "app/.env"
$dotenv = Dotenv::create(ROOT . DS . 'app');
$dotenv->load();

// config file
require_once(ROOT . DS . 'app' . DS . 'config' . DS . 'config.php');

function exceptionHandler($exception, $inspector, $run) {
    $output = "[%datetime%] %channel%.%level_name%: %message% \n%context% %extra%\n";
    $formatter = new LineFormatter($output);
    $formatter->includeStacktraces(true);

    $stream = new StreamHandler(EXCEPTION_LOG);
    $stream->setFormatter($formatter);

    $logger = new Logger('exception_logger');
    $logger->pushHandler($stream);

    $logger->error($exception->getMessage(), [
        'trace' => $exception->getTraceAsString(),
    ]);
}

// Error handling with Whoops and Monolog
$whoops = new Run();
if(ENVIRONMENT == 'development'){
    $handler = new PrettyPageHandler();
    $handler->blacklist('_ENV','DB_USER');
    $handler->blacklist('_ENV','DB_PASSWORD');
    $handler->blacklist('_ENV','DB_HOST');
    $handler->blacklist('_ENV','DB_DATABASE');
    $handler->blacklist('_SERVER','DB_USER');
    $handler->blacklist('_SERVER','DB_PASSWORD');
    $handler->blacklist('_SERVER','DB_HOST');
    $handler->blacklist('_SERVER','DB_DATABASE');
    $whoops->pushHandler($handler);

    $whoops->pushHandler(function ($exception, $inspector, $run) {
        exceptionHandler($exception, $inspector, $run);
    });
}
else{
    $whoops->pushHandler(function ($exception, $inspector, $run) {
        View::view('500');
        exceptionHandler($exception, $inspector, $run);
    });
}
$whoops->register();



// routes
$routes = new Router();
include_once(ROOT . DS . 'app' . DS . 'routes' . DS . 'routes.php');
$routes->run();
