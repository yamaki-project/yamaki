<?php
class YamakiTest extends \PHPUnit_Framework_TestCase
{
    private $obj = null;
    function setUp(){
        $this -> obj = \Yamaki::generate();
    }
    function testGenerate(){
        $this->assertEquals('Yamaki', get_class($this -> obj));
    }

    function testGetRequestForDefault()
    {
        $_SERVER['REQUEST_URI'] = "/yamaki/12345678/12345678/" ;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this -> obj -> get("/yamaki/:ichiban/:niban/:sanban",function($route,$input){
            $this -> assertEquals(1,1);
        });
    }

    function testGetRequest()
    {
        $_SERVER['REQUEST_URI'] = "/yamaki/12345678/87654321/" ;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        try{
            $this -> obj -> get("/yamaki/:ichiban/:niban",function($route,$input){
                throw new Exception($route -> param('ichiban').'/'.$route -> param('niban'));
            });
        }catch(\Exception $e){
            $this -> assertEquals('12345678/87654321',$e -> getMessage());
        }
   }

}
