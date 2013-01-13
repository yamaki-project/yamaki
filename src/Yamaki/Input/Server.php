<?php

namespace Yamaki\Input;

class Server
{

    public function ip()
    {
        return $_SERVER['SERVER_ADDR'];
    }

    public function name()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public function software()
    {
        return $_SERVER['SERVER_SOFTWARE'];
    }

    public function protocol()
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }

    public function admin()
    {
        return $_SERVER['SERVER_ADMIN'];
    }

    public function port()
    {
        return $_SERVER['SERVER_PORT'];
    }

    public function signature()
    {
        return $_SERVER['SERVER_SIGNATURE'];
    }
}
