<?php
class InputTest extends \PHPUnit_Framework_TestCase
{
    private $obj = null;
    function setUp(){
        $this -> obj = \Yamaki\Input::generate();
    }
    function testGenerate(){
        $this->assertEquals('Yamaki\Input', get_class($this -> obj));
    }

    function testMembers(){
        $this->assertEquals('Yamaki\Input\Request', get_class($this -> obj -> request()));
        $this->assertEquals('Yamaki\Input\Client', get_class($this -> obj -> client()));
        $this->assertEquals('Yamaki\Input\Server', get_class($this -> obj -> server()));
    }
}
