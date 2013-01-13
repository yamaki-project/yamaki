<?php

namespace spec\Yamaki\Input;

use PHPSpec2\ObjectBehavior;

class Client extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Yamaki\Input\Client');
    }

    function let()
    {
        $_SERVER = array(
            'HTTP_ACCEPT'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_CHARSET'  => 'Shift_JIS,utf-8;q=0.7,*;q=0.3',
            'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
            'HTTP_ACCEPT_LANGUAGE' => 'en',
            'HTTP_CONNECTION'      => 'keep-alive',                                                                                                   
            'HTTP_REFERER'         => 'http://www.yamaki.org/index.html',                                                                             
            'HTTP_USER_AGENT'      => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17', 
            'HTTPS'                => 'off',
            'REMOTE_ADDR'          => '192.168.0.1',
            'REMOTE_HOST'          => 'yamaki.org',
            'REMOTE_PORT'          => '123456',
            'REMOTE_USER'          => 'freddiefujiwara',
            'REDIRECT_REMOTE_USER' => 'kashimoo',
            'PHP_AUTH_DIGEST'      => '1234',
            'PHP_AUTH_USER'        => 'masuzawashun01',
            'PHP_AUTH_PW'          => 'pass',
            'AUTH_TYPE'            => 'Basic'
        );
    }

    function it_should_exists()
    {
        $this -> accept()    -> shouldBe(array(
            'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'charset'  => 'Shift_JIS,utf-8;q=0.7,*;q=0.3',
            'encoding' => 'gzip,deflate,sdch',
            'language' => 'en'
        ));
        $this -> connection()         -> shouldBe('keep-alive',);
        $this -> referrer()           -> shouldBe('http://www.yamaki.org/index.html',);
        $this -> userAgent()          -> shouldBe('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17',);
        $this -> secure()             -> shouldBe(false););
        $this -> ip()                 -> shouldBe('192.168.0.1',);
        $this -> host()               -> shouldBe('yamaki.org',);
        $this -> port()               -> shouldBe('123456',);
        $this -> user()               -> shouldBe('freddiefujiwara',);
        $this -> redirectRemoteUser() -> shouldBe('kashimoo',);
        $this -> authDigest()         -> shouldBe('1234',);
        $this -> authUser()           -> shouldBe('masuzawashun01',);
        $this -> authPassword()       -> shouldBe('pass',);
        $this -> authType()           -> shouldBe('Basic');
    }

}
