<?php

class Yamaki
{
    public function get($rule,$function)
    {
        $router = new Yamaki\Router();
        $router -> input(new \Yamaki\Input());
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
