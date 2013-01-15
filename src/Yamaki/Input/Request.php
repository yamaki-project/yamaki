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

    public function queryHashByOrder()
    {
        $hash = array();
        foreach(preg_split("/[&;]/",$this -> queryString()) as $keyValue){
            $keyValue = explode("=",$keyValue);
            if(count($keyValue)<2){
                continue;
            }
            $keyValue[1] = urldecode(mb_convert_encoding($keyValue[1], 'UTF-8'));
            if ( array_key_exists($keyValue[0], $hash) ) {
                $hash[$keyValue[0]] = array( $hash[$keyValue[0]], $keyValue[1]);
            } else {
                $hash[$keyValue[0]] = $keyValue[1];
            }
        }
        return $hash;
    }

    public function queryHash()
    {
        parse_str($this -> queryString(), $hash);
        return $hash;
    }
}
