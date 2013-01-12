<?php

namespace Yamaki;

class Route
{
    private $_rule;
    private $_callback;
    private $_paramNames = array();
    private $_param      = array();

    public function map($rule,$callable)
    {
        $this->rule($rule);
        return $this;
    }

    public function rule($rule = null)
    {
        if(is_null($rule)){
          return $this -> _rule;
        }
        if('string' === gettype($rule) 
            && preg_match('/^[:a-zA-Z0-9_%\+\-\/]+$/',$rule)){
          $this -> _rule = $rule;
          return $this;
        }
        throw new \InvalidArgumentException("rule must be string");
    }

    public function callback($callback = null)
    {
        if(is_null($callback)){
          return $this -> _callback;
        }
        if(!is_callable($callback)){
          throw new \InvalidArgumentException("callback must be callable");
        }
        $this -> _callback = $callback;
        return $this;
    }

    public function matches($pathInfo)
    {
        if (!preg_match('@^' . $this -> matchesRegex() . '$@', $pathInfo, $paramValues)) {
          return false;
        }
        foreach ( $this -> _paramNames as $key ) {
            if ( isset($paramValues[$key]) ) {
                $this -> _param[$key] = $paramValues[$key];
            }
        }
        return true;
    }

    public function matchesRegex()
    {
        return preg_replace_callback('@:(\w+)@', array($this, 'matchesRegexMaker'),$this -> rule());
    }

    public function matchesRegexMaker($matches)
    {
        $this -> _paramNames[] = $matches[1];
        return '(?P<'.$matches[1].'>[^/]+)';
    }

    public function param($key)
    {
        return $this -> _param[$key];
    }
}
