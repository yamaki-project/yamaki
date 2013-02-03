<?php
class YamakiTest extends \PHPUnit_Framework_TestCase
{
    private $obj = null;
    function setUp(){
        \Yamaki::clear();
        $this -> obj = \Yamaki::generate();
    }
    function testGenerate(){
        $this->assertEquals('Yamaki', get_class($this -> obj));
    }
    function testClear(){
        $this->assertEquals('Yamaki', get_class($this -> obj));
        $this->assertNull(\Yamaki::clear());
    }

    function testGetRequestForDefault()
    {
        $_SERVER['REQUEST_URI'] = "/yamaki/12345678/12345678/" ;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        try{
            $this -> obj -> get("/yamaki/:ichiban/:niban",function($route,$input){
                throw new Exception($route -> param('ichiban').'/'.$route -> param('niban'));
            },function($route,$input){
                throw new Exception("default");
            });
        }catch(\Exception $e){
            $this -> assertEquals('default',$e -> getMessage());
        }
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
    function testPostRequestForDefault()
    {
        $_SERVER['REQUEST_URI'] = "/yamaki/12345678/12345678/" ;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        try{
            $this -> obj -> post("/yamaki/:ichiban/:niban",function($route,$input){
                throw new Exception($route -> param('ichiban').'/'.$route -> param('niban'));
            },function($route,$input){
                throw new Exception("default");
            });
        }catch(\Exception $e){
            $this -> assertEquals('default',$e -> getMessage());
        }
    }

    function testPostRequest()
    {
        $_SERVER['REQUEST_URI'] = "/yamaki/12345678/87654321/" ;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        try{
            $this -> obj -> post("/yamaki/:ichiban/:niban",function($route,$input){
                throw new Exception($route -> param('ichiban').'/'.$route -> param('niban'));
            });
        }catch(\Exception $e){
            $this -> assertEquals('12345678/87654321',$e -> getMessage());
        }
   }

}
