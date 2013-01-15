<?php

namespace spec\Yamaki\Input;

use PHPSpec2\ObjectBehavior;

class Request extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Yamaki\Input\Request');
    }
    function let()
    {
        $_SERVER = array(
            'REQUEST_METHOD'     => 'GET',
            'REQUEST_TIME'       => '100',
            'REQUEST_TIME_FLOAT' => '99',
            'QUERY_STRING'       => 'k=v&k1=v2&k3=v4',
            'REQUEST_URI'        => '/index.html',
            'PATH_INFO'          => '/hoge/fuga/',
            'ORIG_PATH_INFO'    => '/hoge/fuga/hage'
        );
    }

    function it_should_exists()
    {
        $this -> method()      -> shouldBe('GET');
        $this -> time()        -> shouldBe('100');
        $this -> timeDetail()  -> shouldBe('99');
        $this -> queryString() -> shouldBe('k=v&k1=v2&k3=v4');
        $this -> uri()         -> shouldBe('/index.html');
        $this -> pathInfo()    -> shouldBe('/hoge/fuga/');
        $this -> orgPathInfo() -> shouldBe('/hoge/fuga/hage');
    }

    function it_should_parse_query_by_order()
    {
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=";
        $this->queryHashByOrder()->shouldReturn(array("aaa" => "bbb" , "ccc" => "ddd" , "fff" => "")); 
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=&";
        $this->queryHashByOrder()->shouldReturn(array("aaa" => "bbb" , "ccc" => "ddd" , "fff" => ""));
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=&aaa=ggg";
        $this->queryHashByOrder()->shouldReturn(array("aaa" => array("bbb","ggg") , "ccc" => "ddd" , "fff" => ""));
        $_SERVER['QUERY_STRING'] = "key=%25%26%21%22";
        $this->queryHashByOrder()->shouldReturn(array("key"=>"%&!\""));
        $_SERVER['QUERY_STRING'] = "key=%E8%97%A4%E5%8E%9F";
        $this->queryHashByOrder()->shouldReturn(array("key"=>"藤原"));
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd";
        $this->queryHashByOrder()->shouldReturn(array("aaa"=>"bbb", "ccc"=>"ddd"));
    }

    function it_should_parse_query() {
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=";
        $this->queryHash()->shouldReturn(array("aaa" => "bbb" , "ccc" => "ddd" , "fff" => "")); 
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=&";
        $this->queryHash()->shouldReturn(array("aaa" => "bbb" , "ccc" => "ddd" , "fff" => ""));
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd&fff=&aaa=ggg";
        $this->queryHash()->shouldReturn(array("aaa" => "ggg" , "ccc" => "ddd" , "fff" => ""));
        $_SERVER['QUERY_STRING'] = "key=%25%26%21%22";
        $this->queryHash()->shouldReturn(array("key"=>"%&!\""));
        $_SERVER['QUERY_STRING'] = "key=%E8%97%A4%E5%8E%9F";
        $this->queryHash()->shouldReturn(array("key"=>"藤原"));
        $_SERVER['QUERY_STRING'] = "aaa=bbb&ccc=ddd";
        $this->queryHash()->shouldReturn(array("aaa"=>"bbb", "ccc"=>"ddd"));
    }
}
