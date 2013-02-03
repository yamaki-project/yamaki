<?php
class RouterTest extends \PHPUnit_Framework_TestCase
{
    private $obj = null;
    function setUp(){
        \Yamaki\Router::clear();
        $this -> obj = \Yamaki\Router::generate();
    }
    function testGenerate(){
        $this->assertEquals('Yamaki\Router', get_class($this -> obj));
    }
    function testClear(){
        $this->assertEquals('Yamaki\Router', get_class($this -> obj));
        $this->assertNull(\Yamaki\Router::clear());
    }
    function testDispatchViaMethod()
    {
        $_SERVER['REQUEST_URI'] = "/hoge/12345678.12345678/" ;
        $this -> obj -> input(\Yamaki\Input::generate());

        $getRoute = \Yamaki\Route::generate()
                -> rule("/hoge/:fuga/")
                -> viaGet()
                -> callback(function(){});
        $this -> assertEquals(get_class($this -> obj -> put($getRoute)),'Yamaki\Router');

        $postRoute = \Yamaki\Route::generate()
                -> rule("/hoge/:fuga/")
                -> viaPost()
                -> callback(function(){});
        $this -> obj -> put($postRoute);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this -> assertEquals(get_class($this -> obj -> dispatch()),'Yamaki\Route');
        $this -> assertEquals($this -> obj -> dispatch(),$postRoute);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this -> assertEquals(get_class($this -> obj -> dispatch()),'Yamaki\Route');
        $this -> assertEquals($this -> obj -> dispatch(),$getRoute);

        $_SERVER['REQUEST_URI'] = "/fuga/12345678.12345678/" ;
        $this -> obj -> input(\Yamaki\Input::generate());

        $route = \Yamaki\Route::generate()
                -> rule("/fuga/:hoge")
                -> callback(function(){});
        $this -> obj -> put($route);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this -> assertEquals(get_class($this -> obj -> dispatch()),'Yamaki\Route');
        $this -> assertEquals($this -> obj -> dispatch(),$route);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this -> assertEquals(get_class($this -> obj -> dispatch()),'Yamaki\Route');
        $this -> assertEquals($this -> obj -> dispatch(),$route);
    }
    function testRoute()
    {
        $_SERVER['REQUEST_URI'] = "/hoge/12345678.12345678/" ;
        $this -> obj -> input(\Yamaki\Input::generate());

        $willMatcheRoute = \Yamaki\Route::generate()
                -> rule("/hoge/:fuga/")
                -> callback(function(){});
        $this -> obj -> put($willMatcheRoute);

        $this -> obj -> put(\Yamaki\Route::generate()
                -> rule("/hoge2/:fuga/")
                -> viaGet()
                -> callback(function(){}));

        try{
            $this -> obj -> put(\Yamaki\Route::generate()
                -> rule("/hoge/:fuga/")
                -> callback(function(){}));
        }catch(\InvalidArgumentException $e){
            $this -> assertEquals($e -> getMessage(),"same rule not be allowed");
        }

        try{
            $this -> obj -> put(\Yamaki\Route::generate()
                -> rule("/hoge/:fuga")
                -> callback(function(){}));
        }catch(\InvalidArgumentException $e){
            $this -> assertEquals($e -> getMessage(),"same rule not be allowed");
        }

        try{
            $this -> obj -> put(\Yamaki\Route::generate()
                -> rule("/hoge2/:fuga")
                -> callback(function(){}));
        }catch(\InvalidArgumentException $e){
            $this -> assertEquals($e -> getMessage(),"same rule not be allowed");
        }

        try{
            $this -> obj -> put(\Yamaki\Route::generate()
                -> rule("/hoge2/:fuga")
                -> viaPost()
                -> callback(function(){}));
            $this -> assertTrue(true);
        }catch(\InvalidArgumentException $e){
            //should not throw
            $this -> assertFlase(true);
        }

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this -> assertEquals(get_class($this -> obj -> dispatch()),'Yamaki\Route');
        $this -> assertEquals($this -> obj -> dispatch(),$willMatcheRoute);
    }
    function testDefaultRoute()
    {
        $_SERVER['REQUEST_URI'] = "/hoge/12345678.12345678/" ;
        $this -> obj -> input(\Yamaki\Input::generate());


        $route1 = \Yamaki\Route::generate()
                -> rule("/hoge1/:fuga/")
                -> callback(function(){});
        $this -> assertEquals(get_class($this-> obj -> defaultRoute($route1)
               ),'Yamaki\Router');

        $route2 = \Yamaki\Route::generate()
                -> rule("/hoge2/:fuga/")
                -> callback(function(){});
        $this -> obj -> put($route2);

        $this -> assertEquals(get_class($this -> obj -> dispatch()),'Yamaki\Route');
        $this -> assertEquals($this -> obj -> dispatch(),$route1);
    }
    function it_should_have_route_and_dispatch()
    {
        $_SERVER['REQUEST_URI'] = "/hoge/12345678.12345678/?url=http%3a%2f%2ftest.jp/path";
        $this -> obj -> input(\Yamaki\Input::generate());

        $willMatcheRoute = \Yamaki\Route::generate()
                -> rule("/hoge/:fuga/")
                -> callback(function(){});
        $this -> obj -> put($willMatcheRoute);
        $this -> assertEquals(get_class($this -> obj -> dispatch()),'Yamaki\Route');
        $this -> assertEquals($this -> obj -> dispatch(),$willMatcheRoute);

    }
}
