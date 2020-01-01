<?php

namespace App\Core;

class View{
    private static $loader;
    private static $twig;
    private static $extension;
    
    private static function init(){
        require_once ROOT . DS . 'app' . DS . 'config' . DS . 'twig.php';
        self::$loader = new \Twig\Loader\FilesystemLoader($config['twig']['template_path']);
        self::$twig = new \Twig\Environment(self::$loader, $config['twig']['enviornment']);
        self::$twig->addGlobal('SITE_TITLE', getenv('SITE_TITLE'));
        self::$extension = $config['twig']['extension'];
    }

    public static function view($file,$data=[]){
        self::init();
        echo self::$twig->render($file.'.'.self::$extension,$data);
    }
}