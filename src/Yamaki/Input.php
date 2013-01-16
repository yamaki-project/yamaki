<?php

namespace Yamaki;

class Input
{
    private $_request;
    private $_client;
    private $_server;

    private static $instance;

    public static function generate()
    {
        return isset(self::$instance) ? 
            self::$instance : 
            self::$instance = new self();
    }

    public function __construct()
    {
        $this -> _request = Input\Request::generate();
        $this -> _server  = Input\Server::generate();
        $this -> _client  = Input\Client::generate();
    }

    public function request()
    {
        return $this -> _request;
    }

    public function server()
    {
        return $this -> _server;
    }

    public function client()
    {
        return $this -> _client;
    }
}
