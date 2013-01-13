<?php

namespace Yamaki\Input;

class Request
{

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function time()
    {
        return $_SERVER['REQUEST_TIME'];
    }

    public function timeDetail()
    {
        return $_SERVER['REQUEST_TIME_FLOAT'];
    }

    public function queryString()
    {
        return $_SERVER['QUERY_STRING'];
    }

    public function uri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function pathInfo()
    {
        return $_SERVER['PATH_INFO'];
    }

    public function orgPathInfo()
    {
        return $_SERVER['ORIG_PATH_INFO'];
    }
}
