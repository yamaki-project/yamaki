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
            'ORIG_PATH_INFO'     => '/hoge/fuga/hage'
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
}
