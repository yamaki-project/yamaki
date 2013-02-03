<?php
class RequestTest extends \PHPUnit_Framework_TestCase
{
    private $obj = null;
    function setUp(){
        $_SERVER = array(
            'REQUEST_METHOD'     => 'GET',
            'REQUEST_TIME'       => '100',
            'REQUEST_TIME_FLOAT' => '99',
            'QUERY_STRING'       => 'k=v&k1=v2&k3=v4',
            'REQUEST_URI'        => '/index.html',
            'PATH_INFO'          => '/hoge/fuga/',
            'ORIG_PATH_INFO'    => '/hoge/fuga/hage'
        );
        $this -> obj = \Yamaki\Input\Request::generate();
    }
    function testGenerate(){
        $this->assertEquals('Yamaki\Input\Request', get_class($this -> obj));
    }

    function testExists()
    {
        $this -> assertEquals($this -> obj -> method()      ,'GET');
        $this -> assertEquals($this -> obj -> time()        ,'100');
        $this -> assertEquals($this -> obj -> timeDetail()  ,'99');
        $this -> assertEquals($this -> obj -> queryString() ,'k=v&k1=v2&k3=v4');
        $this -> assertEquals($this -> obj -> uri()         ,'/index.html');
        $this -> assertEquals($this -> obj -> pathInfo()    ,'/hoge/fuga/');
        $this -> assertEquals($this -> obj -> orgPathInfo() ,'/hoge/fuga/hage');
    }

    function testParseQueryByOrder()
    {
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=";
        $this -> assertEquals($this -> obj->queryHashByOrder(),array("aaa" => "bbb" , "ccc" => "ddd" , "fff" => "")); 
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=&";
        $this -> assertEquals($this -> obj->queryHashByOrder(),array("aaa" => "bbb" , "ccc" => "ddd" , "fff" => ""));
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=&aaa=ggg";
        $this -> assertEquals($this -> obj->queryHashByOrder(),array("aaa" => array("bbb","ggg") , "ccc" => "ddd" , "fff" => ""));
        $_SERVER['QUERY_STRING'] = "key=%25%26%21%22";
        $this -> assertEquals($this -> obj->queryHashByOrder(),array("key"=>"%&!\""));
        $_SERVER['QUERY_STRING'] = "key=%E8%97%A4%E5%8E%9F";
        $this -> assertEquals($this -> obj->queryHashByOrder(),array("key"=>"藤原"));
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd";
        $this -> assertEquals($this -> obj->queryHashByOrder(),array("aaa"=>"bbb", "ccc"=>"ddd"));
        $this -> assertEquals($this -> obj->queryHashByOrder("aaa=bbb&ccc=ddd&fff="),array("aaa" => "bbb" , "ccc" => "ddd" , "fff" => "")); 
    }

    function testParseQuery() {
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=";
        $this -> assertEquals($this -> obj->queryHash(),array("aaa" => "bbb" , "ccc" => "ddd" , "fff" => "")); 
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=&";
        $this -> assertEquals($this -> obj->queryHash(),array("aaa" => "bbb" , "ccc" => "ddd" , "fff" => ""));
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=&aaa=ggg";
        $this -> assertEquals($this -> obj->queryHash(),array("aaa" => "ggg" , "ccc" => "ddd" , "fff" => ""));
        $_SERVER['QUERY_STRING'] = "key=%25%26%21%22";
        $this -> assertEquals($this -> obj->queryHash(),array("key"=>"%&!\""));
        $_SERVER['QUERY_STRING'] = "key=%E8%97%A4%E5%8E%9F";
        $this -> assertEquals($this -> obj->queryHash(),array("key"=>"藤原"));
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd";
        $this -> assertEquals($this -> obj->queryHash(),array("aaa"=>"bbb", "ccc"=>"ddd"));
    }
}
