<?php

namespace spec;

use PHPSpec2\ObjectBehavior;

class Yamaki extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Yamaki');
    }

    function it_should_be_singleton()
    {
        $instance = $this->generate();
        $instance -> shouldHaveType('Yamaki');
        $this->generate() -> shouldBe($instance);
    }

    function it_should_get_request_for_default()
    {
        $_SERVER['REQUEST_URI'] = "/yamaki/12345678/12345678/" ;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this -> get("/yamaki/:ichiban/:niban",function($route,$input){
            echo __FILE__.":".__LINE__."\n";
        });
    }

    function it_should_get_request()
    {
        $_SERVER['REQUEST_URI'] = "/yamaki/12345678/12345678/" ;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this -> get("/yamaki/:ichiban/:niban",function($route,$input){
            echo __FILE__.":".__LINE__."\n";
        });
    }
}
