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

    public static function clear()
    {
        Yamaki\Router::clear();
        return isset(self::$instance) ? 
            self::$instance = null : null;
    }

    public function get($rule,$function,$default = null ){
        $default = is_null($default) ? 
                   function($route,$input){echo "404 Not Found\n";} :
                   $default;
        return Yamaki\Router::generate()
                -> input(\Yamaki\Input::generate())
                -> defaultRoute(\Yamaki\Route::generate()
                    -> callback($default)
                   )
                -> put(\Yamaki\Route::generate()
                    -> rule($rule)
                    -> viaGet()
                    -> callback($function))
                -> dispatch();
    }

    public function post($rule,$function,$default = null ){
        $default = is_null($default) ? 
                   function($route,$input){echo "404 Not Found\n";} :
                   $default;
        return Yamaki\Router::generate()
                -> input(\Yamaki\Input::generate())
                -> defaultRoute(\Yamaki\Route::generate()
                    -> callback($default)
                   )
                -> put(\Yamaki\Route::generate()
                    -> rule($rule)
                    -> viaPost()
                    -> callback($function))
                -> dispatch();
    }
}
