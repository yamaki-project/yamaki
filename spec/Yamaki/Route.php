<?php

namespace spec\Yamaki;

use PHPSpec2\ObjectBehavior;

class Route extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Yamaki\Route');
    }

    function it_should_set_rule()
    {
        $rule = "/yamaki/:ichiban/:niban";
        $this -> rule($rule)
              -> rule()
              -> shouldBe($rule);
        $this -> shouldThrow(new \InvalidArgumentException("callback must be callable")) -> duringCallback('test');
        $this -> shouldThrow(new \InvalidArgumentException("rule must be string")) -> duringRule(function () {});
    }

    function it_should_set_callback()
    {
        $callback = function () {};
        $this -> callback($callback)
              -> callback()
              -> shouldBe($callback);
        $this -> shouldThrow(new \InvalidArgumentException("callback must be callable")) -> duringCallback('test');
    }

    function it_should_set_no_submatches()
    {
        $noSubmatches = array('foo','bar');
        $this -> noSubmatches($noSubmatches)
              -> noSubmatches()
              -> shouldBe($noSubmatches);
        $this -> shouldThrow(new \InvalidArgumentException("noSubmatches must be array")) -> duringnoSubmatches('test');
    }

    function it_should_be_made_regex_for_matches()
    {
        $rule = "/yamaki/:ichiban/:niban";
        $this -> rule($rule)
              -> matchesRegex()
              -> shouldBe('/yamaki/(?P<ichiban>[^/]+)/(?P<niban>[^/]+)/?');
    }

    function it_should_be_made_regex_for_splitted_rules()
    {
        $matches = array(
            ':ichiban',
            'ichiban'
        );
        $this -> matchesRegexMaker($matches)
              -> shouldBe('(?P<ichiban>[^/]+)');
    }

    function it_should_get_as_params()
    {
        $rule = "/yamaki/:ichiban/:niban";
        $this -> rule($rule)
              -> matches('/yamaki/1/2')->shouldbe(true);
        $this -> param('ichiban') -> shouldBe('1');
        $this -> param('niban')  -> shouldBe('2');

        $this -> matches('/yamaki/aaa/bbb')->shouldbe(true);
        $this -> param('ichiban') -> shouldBe('aaa');
        $this -> param('niban')  -> shouldBe('bbb');

        $this -> matches('/yamaki/aaa/bbb/')->shouldbe(true);
        $this -> param('ichiban') -> shouldBe('aaa');
        $this -> param('niban')  -> shouldBe('bbb');

        $this -> matches('/yamaki/%E3%82%84%E3%81%BE%E3%81%8D/%E6%97%85%E9%A4%A8')->shouldbe(true);
        $this -> param('ichiban') -> shouldBe('やまき');
        $this -> param('niban')  -> shouldBe('旅館');

        $this -> matches('/yamaki/aaa/bbb')->shouldbe(true);
        $this -> param('ccc') -> shouldBe(null);

        $this -> matches('/yamaki/aaa.m=mval.c=cval/t=tval.u=uval.bbb')->shouldbe(true);
        $this -> param('ichiban') -> shouldBe(array(
            'aaa',
            'm' => 'mval',
            'c' => 'cval'
        ));
        $this -> param('niban') -> shouldBe(array(
            't' => 'tval',
            'u' => 'uval',
            'bbb'
        ));

        $this -> noSubmatches(array('niban'))
              -> matches('/yamaki/aaa.m=mval.c=cval/t=tval.u=uval.bbb')->shouldbe(true);
        $this -> param('ichiban') -> shouldBe(array(
            'aaa',
            'm' => 'mval',
            'c' => 'cval'
        ));
        $this -> param('niban') -> shouldBe(
            't=tval.u=uval.bbb'
        );
    }

    function it_should_get_as_sub_params()
    {
        $this -> subMatches('aaa.b=bval.c=cval')->shouldbe(array(
            'aaa',
            'b' => 'bval',
            'c' => 'cval'
        ));

        $this -> subMatches('aaa.b=bval.c=')->shouldbe(array(
            'aaa',
            'b' => 'bval',
            'c' => ''
        ));

        $this -> subMatches('aaa.b=bval.=cval')->shouldbe(array(
            'aaa',
            'b' => 'bval'
        ));

        $this -> subMatches('aaa.b=bval...c=cval')->shouldbe(array(
            'aaa',
            'b' => 'bval',
            'c' => 'cval'
        ));

        $this -> subMatches('aaa.b=bval.=.c=cval')->shouldbe(array(
            'aaa',
            'b' => 'bval',
            'c' => 'cval'
        ));

        $this -> subMatches('.aaa.')->shouldbe(array(
            'aaa'
        ));

        $this -> subMatches('aaa')->shouldbe(
            'aaa'
        );
    }

    function it_should_run(){
        $rule = "/yamaki/:ichiban/:niban";
        $this -> rule($rule)
              -> callback(function($route){
                     $route -> param('ichiban');
                     $route -> param('niban');
        })
              -> matches('/yamaki/1/2')->shouldbe(true);
        $this -> run();
    }
}
