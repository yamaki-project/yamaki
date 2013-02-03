<?php
class ServerTest extends \PHPUnit_Framework_TestCase
{
    private $obj = null;
    function setUp(){
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
        $this -> obj = \Yamaki\Input\Server::generate();
    }
    function testGenerate(){
        $this->assertEquals('Yamaki\Input\Server', get_class($this -> obj));
    }

    function testexists()
    {
        $this -> assertEquals($this -> obj -> ip()             ,'192.168.1.1');
        $this -> assertEquals($this -> obj -> name()           ,'yamaki.org');
        $this -> assertEquals($this -> obj -> software()       ,'nginx');
        $this -> assertEquals($this -> obj -> protocol()       ,'http');
        $this -> assertEquals($this -> obj -> admin()          ,'webmaster@yamaki.org');
        $this -> assertEquals($this -> obj -> port()           ,'80');
        $this -> assertEquals($this -> obj -> signature()      ,'sig');
        $this -> assertEquals($this -> obj -> gawayInterface() ,'255.255.255.0');
        $this -> assertEquals($this -> obj -> documentRoot()   ,'/home/yamaki');
        $this -> assertEquals($this -> obj -> scriptFilename() ,'yamaki');
        $this -> assertEquals($this -> obj -> pathTranslated() ,'/hoge/fuga');
    }

}
