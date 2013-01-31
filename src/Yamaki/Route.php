<?php

namespace Yamaki;

class Route
{
    private $_rule;
    private $_via = array('GET','POST');
    private $_callback;
    private $_noSubmatches = array();
    private $_noDecodes    = array();
    private $_paramNames   = array();
    private $_params       = array();

    public static function generate()
    {
        return new self();
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

    public function viaGet()
    {
        return $this -> via('GET');
    }

    public function viaPost()
    {
        return $this -> via('POST');
    }

    public function via($via = null)
    {
        if(is_null($via)){
          return $this -> _via;
        }
        if(in_array($via,array(
            'GET',
            'POST'
        ))){
          $this -> _via = array($via);
          return $this;
        }
        throw new \InvalidArgumentException("via must be GET or POST");
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

    public function noSubmatches($noSubmatches = null)
    {
        if(is_null($noSubmatches)){
          return $this -> _noSubmatches;
        }
        if(!is_array($noSubmatches)){
          throw new \InvalidArgumentException("noSubmatches must be array");
        }
        $this -> _noSubmatches = $noSubmatches;
        return $this;
    }

    public function noDecodes($noDecodes = null)
    {
        if(is_null($noDecodes)){
          return $this -> _noDecodes;
        }
        if(!is_array($noDecodes)){
          throw new \InvalidArgumentException("noDecodes must be array");
        }
        $this -> _noDecodes = $noDecodes;
        return $this;
    }

    public function matches($pathInfo)
    {
        $tmp = explode("?", $pathInfo);
        $pathInfo = $tmp[0];
        if (!preg_match('@^' . $this -> matchesRegex() . '$@', $pathInfo, $paramValues)) {
          return false;
        }
        foreach ( $this -> _paramNames as $key ) {
            if ( isset($paramValues[$key]) ) {
                $value = in_array($key, $this -> noDecodes()) ? $paramValues[$key] : urldecode($paramValues[$key]);
                $this -> _params[$key] = in_array($key, $this -> noSubmatches()) ? $value : $this -> subMatches($value);
            }
        }
        return true;
    }

    public function matchesRegex()
    {
        $matchesRegex  = preg_replace_callback('@:(\w+)@', array($this, 'matchesRegexMaker'),$this -> rule());
        $matchesRegex .= preg_match('@/$@',$matchesRegex) ? '?' : '/?';
        return $matchesRegex;
    }

    public function matchesRegexMaker($matches)
    {
        $this -> _paramNames[] = $matches[1];
        return '(?P<'.$matches[1].'>[^/]+)';
    }

    public function param($key)
    {
        if ( !array_key_exists($key, $this -> _params) ) {
            return null;
        }
        return $this -> _params[$key];
    }

    public function subMatches($param)
    {
        if(!preg_match('/\./',$param)){
            return $param;
        }
        $subParams = array();
        $subParamCandidates = explode(".", $param);
        foreach($subParamCandidates as $subParamCandidate){
            if(preg_match('/=/',$subParamCandidate)){
                $keyValue = explode("=", $subParamCandidate);
                if(empty($keyValue[0])) { continue; }
                $subParams[$keyValue[0]] = $keyValue[1];
                continue;
            }
            if(empty($subParamCandidate)) { continue; }
            $subParams[] = $subParamCandidate;
        }
        return $subParams;
    }

    public function run($input)
    {
       call_user_func_array($this -> callback(),array($this,$input)); 
    }
}
