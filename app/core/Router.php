<?php

namespace App\Core;

use App\Core\Request;
use App\Exceptions\RouterException;
use App\Core\View;

class Router
{
    private $routes;
    private $_request;

    public function __construct()
    {
        $this->_request = new Request();
    }

    private function request($method, $route, $controller, $function)
    {
        $this->routes[$method][$route] = [$controller,$function];
    }

    public function all()
    {
        return $this->routes;
    }

    public function __call($method, $args)
    {
        $method = strtolower($method);
        $allowedMethods = ['post','get','put','delete'];
        if (!in_array($method, $allowedMethods)) {
            throw new InvalidMethodRequestException();
        }

        $controller_function = explode('@', $args[1]);

        $controller = ltrim($controller_function[0], '/');
        $function = $controller_function[1];

        $this->request($method, $args[0], $controller, $function);
    }

    public function run()
    {
        
        $method = $this->_request->getMethod();
        $url = $this->_request->getPath();
        $query = $this->_request->getQuery();
        $controller = null;
        $controller_function = null;

        if (array_key_exists($method, $this->routes)) {
            foreach ($this->routes[$method] as $route => $array) {
                $route_array = explode('/', $route);
                $url_array = explode('/', $url);

                // size of route and url has to be the same
                if (sizeof($route_array) == sizeof($url_array)) {

                    $route_found = false;

                    // loop through each route parts
                    for ($i = 0; $i<sizeof($route_array); $i++) {
                        // if route part is same as url part, continue to next part
                        if($url_array[$i] == $route_array[$i]){
                            $route_found = true;
                        }
                        // if route part is a variable {var_name}, ignore or store it for later
                        elseif (preg_match('/{(.*?)}/', $route_array[$i], $match) === 1 && strlen($url_array[$i]) > 0) {
                            $this->_request->setParam($match[1],$url_array[$i]);
                            $route_found = true;
                        }
                        else{
                            $route = false;
                        }
                    }

                    if($route_found){
                        $controller = $array[0];
                        $controller_function = $array[1];
                    }
                }
            }
        }
        
        if(!is_null($controller) && !is_null($controller_function)){
            if (class_exists($controller)) {
                $dispatch = new $controller($controller, $controller_function);
            }
            else{
                throw new \Exception("Controller {$controller} not found");
            }

            if(method_exists($controller,$controller_function)){
                call_user_func([$dispatch,$controller_function],$this->_request);
            }
            else{
                throw new \Exception("function {$controller_function} does not exits in {$controller}.php");
            }
            
        }
        else{
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
            View::view('404');
        }
    }
}