<?php

namespace App\Core;

use App\Core\Twig;

class Response{
    public static function view($file,$data=[])
    {
        Twig::view($file,$data);
    }

    public static function redirect($url,$statusCode=null)
    {
        isset($statusCode) ? header("Location: {$url}", true, $statusCode) : header("Location : {$url}");
        exit();
    }

    public static function show404()
    {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true , 404);
        self::view('404');
        exit();
    }
}