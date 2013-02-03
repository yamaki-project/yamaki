<?php

namespace Yamaki;

class Router
{
    private $_input;
    private $_defaultRoute;
    private $_routes = array();
    private static $instance;

    public static function clear()
    {
        return isset(self::$instance) ? 
            self::$instance = null : null;
    }

    public static function generate()
    {
        return isset(self::$instance) ? 
            self::$instance : 
            self::$instance = new self();
    }

    public function put($route)
    {
        foreach($this -> _routes as  $compareWithRoute){
            if($compareWithRoute -> matchesRegex() === $route -> matchesRegex()){
                foreach($compareWithRoute -> via() as $compareWithVia){
                    if(in_array($compareWithVia,$route -> via())){
                        throw new \InvalidArgumentException("same rule not be allowed");
                    }
                }
            }
        }
        $this -> _routes[] = $route;
        return $this;
    }

    public function input($input = null)
    {
        if(is_null($input)){
          return $this -> _input;
        }
        $this -> _input = $input;
        return $this;
    }

    public function dispatch()
    {
        foreach( $this -> _routes as $route ) {
          $request = $this -> input() -> request();
          if (  $route -> matches($request -> uri())       &&
                in_array($request -> method(),$route -> via())){

                $route->run($this -> input());
                return $route;
            }
        }
        $this -> defaultRoute() -> run($this -> input());
        return $this -> defaultRoute();
    }

    public function defaultRoute($defaultRoute = null)
    {
        if(is_null($defaultRoute)){
          return $this -> _defaultRoute;
        }
        $this -> _defaultRoute = $defaultRoute;
        return $this;
    }
}
