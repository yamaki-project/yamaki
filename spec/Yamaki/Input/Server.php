<?php

namespace spec\Yamaki\Input;

use PHPSpec2\ObjectBehavior;

class Server extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Yamaki\Input\Server');
    }

    function let()
    {
        $_SERVER = array(
            'GATEWAY_INTERFACE' => "255.255.255.0",
            'DOCUMENT_ROOT'     => "/home/yamaki",
            'SCRIPT_FILENAME'   => "yamaki",
            'PATH_TRANSLATED'   => "/hoge/fuga",
            'SERVER_ADDR'       => '192.168.1.1',
            'SERVER_NAME'       => 'yamaki.org',
            'SERVER_SOFTWARE'   => 'nginx',
            'SERVER_PROTOCOL'   => 'http',
            'SERVER_ADMIN'      => 'webmaster@yamaki.org',
            'SERVER_PORT'       => '80',
            'SERVER_SIGNATURE'  => 'sig',
        );
    }

    function it_should_exists()
    {
        $this -> ip()             -> shouldBe('192.168.1.1');
        $this -> name()           -> shouldBe('yamaki.org');
        $this -> software()       -> shouldBe('nginx');
        $this -> protocol()       -> shouldBe('http');
        $this -> admin()          -> shouldBe('webmaster@yamaki.org');
        $this -> port()           -> shouldBe('80');
        $this -> signature()      -> shouldBe('sig');
        $this -> gawayInterface() -> shouldBe('255.255.255.0');
        $this -> documentRoot()   -> shouldBe('/home/yamaki');
        $this -> scriptFilename() -> shouldBe('yamaki');
        $this -> pathTranslated() -> shouldBe('/hoge/fuga');
    }

}
