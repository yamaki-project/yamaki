<?php

namespace Yamaki;

class Router
{
    private $_input;
    private $_defaultRoute ;
    private $_routes = array();

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
    }

    public function input($input)
    {
        $this -> _input = $input;
    }

    public function dispatch()
    {
        foreach( $this -> _routes as $route ) {
          $request = $this -> _input -> request();
          if (  $route -> matches($request -> uri())       &&
                in_array($request -> method(),$route -> via())){

                $route->run($this -> _input);
                return $route;
            }
        }
        $this -> _defaultRoute -> run($this -> _input);
        return $this -> _defaultRoute;
    }

    public function defaultRoute($default)
    {
        $this -> _defaultRoute = $default;
    }
}