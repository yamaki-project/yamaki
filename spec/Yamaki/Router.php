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

        $route1 = new \Yamaki\Route();
        $route1 -> rule("/hoge2/:fuga/")
                -> callback(function(){});
        $this -> put($route1);

        $route2  = new \Yamaki\Route();
        $route2 -> rule("/hoge/:fuga/")
                -> callback(function(){});
        $this -> put($route2);

        $route3  = new \Yamaki\Route();
        $route3 -> rule("/hoge/:fuga/")
                -> callback(function(){});
        $this -> shouldThrow(new \InvalidArgumentException("same rule not be allowed")) -> duringPut($route3);

        $route4  = new \Yamaki\Route();
        $route4 -> rule("/hoge/:fuga")
                -> callback(function(){});
        $this -> shouldThrow(new \InvalidArgumentException("same rule not be allowed")) -> duringPut($route4);

        $this -> dispatch() -> shouldHaveType('Yamaki\Route');
        $this -> dispatch() -> shouldBe($route2);
    }

    function it_should_have_default_route()
    {
        $_SERVER['PATH_INFO'] = "/hoge/12345678.12345678/" ;
        $this -> input(new \Yamaki\Input());


        $route1 = new \Yamaki\Route();
        $route1 -> rule("/hoge1/:fuga/")
                -> callback(function(){});
        $this -> defaultRoute($route1);

        $route2  = new \Yamaki\Route();
        $route2 -> rule("/hoge2/:fuga/")
                -> callback(function(){});
        $this -> put($route2);

        $this -> dispatch() -> shouldHaveType('Yamaki\Route');
        $this -> dispatch() -> shouldBe($route1);
    }

}
