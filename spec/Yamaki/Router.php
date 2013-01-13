<?php

namespace spec\Yamaki;

use PHPSpec2\ObjectBehavior;

class Router extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Yamaki\Router');
    }

    function it_should_have_route()
    {
        $_SERVER['PATH_INFO'] = "/hoge/12345678.12345678/" ;
        $this -> input(new \Yamaki\Input());

        $willMatcheRoute = \Yamaki\Route::generate()
                -> rule("/hoge/:fuga/")
                -> callback(function(){});
        $this -> put($willMatcheRoute);

        $this -> put(\Yamaki\Route::generate()
                -> rule("/hoge2/:fuga/")
                -> viaGet()
                -> callback(function(){}));

        $this -> shouldThrow(new \InvalidArgumentException("same rule not be allowed"))
                -> duringPut(\Yamaki\Route::generate()
                -> rule("/hoge/:fuga/")
                -> callback(function(){}));

        $this -> shouldThrow(new \InvalidArgumentException("same rule not be allowed"))
                -> duringPut(\Yamaki\Route::generate()
                -> rule("/hoge/:fuga")
                -> callback(function(){}));

        $this -> shouldThrow(new \InvalidArgumentException("same rule not be allowed"))
                -> duringPut(\Yamaki\Route::generate()
                -> rule("/hoge2/:fuga")
                -> callback(function(){}));

        $this -> shouldNotThrow(new \InvalidArgumentException("same rule not be allowed"))
                -> duringPut(\Yamaki\Route::generate()
                -> rule("/hoge2/:fuga")
                -> viaPost()
                -> callback(function(){}));

        $this -> dispatch() -> shouldHaveType('Yamaki\Route');
        $this -> dispatch() -> shouldBe($willMatcheRoute);
    }

    function it_should_have_default_route()
    {
        $_SERVER['PATH_INFO'] = "/hoge/12345678.12345678/" ;
        $this -> input(new \Yamaki\Input());


        $route1 = \Yamaki\Route::generate()
                -> rule("/hoge1/:fuga/")
                -> callback(function(){});
        $this -> defaultRoute($route1);

        $route2 = \Yamaki\Route::generate()
                -> rule("/hoge2/:fuga/")
                -> callback(function(){});
        $this -> put($route2);

        $this -> dispatch() -> shouldHaveType('Yamaki\Route');
        $this -> dispatch() -> shouldBe($route1);
    }

}
