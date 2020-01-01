<?php

namespace App\Core;

class Session
{
    //https://en.wikipedia.org/wiki/Session_fixation#A_simple_attack_scenario

    
    public static function has($name)
    {
        return isset($_SESSION[$name]);
    }
    
    public static function get($name)
    {
        return $_SESSION[$name];
    }

    public static function pull($name)
    {
        $value = self::get($name);
        self::delete($name);
        return $value;
    }
    
    public static function put($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    public static function push($name, $value)
    {
        $array = explode('.',$name);
        return $_SESSION[$array[0]][$array[1]] = $value;
    }
    
    public static function delete($name)
    {
        if (self::has($name)) {
            unset($_SESSION[$name]);
        }
    }
    
    public static function uagent_no_version()
    {
        $uagent = $_SERVER['HTTP_USER_AGENT'];
        $regx = '/\/[a-zA-Z0-9.]+/';
        $newString = preg_replace($regx, '', $uagent);
        return $newString;
    }
}
