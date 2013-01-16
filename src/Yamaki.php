<?php

class Yamaki
{
    private static $instance;

    public static function generate()
    {
        return isset(self::$instance) ? 
            self::$instance : 
            self::$instance = new self();
    }
    public function get($rule,$function)
    {
        $router =  Yamaki\Router::generate()
                -> input(\Yamaki\Input::generate())
                -> defaultRoute(\Yamaki\Route::generate()
                    -> callback(function($route,$input){
                            echo "404 Not Found\n";
                        })
                   )
                -> put(\Yamaki\Route::generate()
                    -> rule($rule)
                    -> viaGet()
                    -> callback($function))
                -> dispatch();
    }
}
