<?php

namespace Yamaki;

class Router
{
    private $_input;
    private $_defaultRoute ;
    private $_routes = array();

    public function put($route)
    {
        if(in_array($route -> matchesRegex(),array_keys($this -> _routes))){
            throw new \InvalidArgumentException("same rule not be allowed");
        }
        $this -> _routes[$route -> matchesRegex() ] = $route;
    }

    public function input($input)
    {
        $this -> _input = $input;
    }

    public function dispatch()
    {
        foreach( array_values($this -> _routes) as $route ) {
            if ( $route -> matches($this -> _input -> request()-> pathinfo())) {
                $route->run();
                return $route;
            }
        }
        $this -> _defaultRoute -> run();
        return $this -> _defaultRoute;
    }

    public function defaultRoute($default)
    {
        $this -> _defaultRoute = $default;
    }
}
