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
        $router = new Yamaki\Router();
        $router -> input(\Yamaki\Input::generate());
        $router -> defaultRoute(\Yamaki\Route::generate()
                    -> callback(function($route,$input){
                            echo "404 Not Found\n";
                        })
                   );
        $router -> put(\Yamaki\Route::generate()
                    -> rule($rule)
                    -> viaGet()
                    -> callback($function));
        $router -> dispatch();
    }
}
