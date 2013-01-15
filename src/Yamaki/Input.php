<?php

namespace Yamaki;

class Input
{
    private $_request;
    private $_client;
    private $_server;

    public function __construct()
    {
        $this -> _request = new Input\Request();
        $this -> _server  = new Input\Server();
        $this -> _client  = new Input\Client();
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
