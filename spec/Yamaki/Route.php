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
        $rule = "/hello/:first/:last";
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

    function it_should_be_made_regex_for_matches()
    {
        $rule = "/hello/:first/:last";
        $this -> rule($rule)
              -> matchesRegex()
              -> shouldBe('/hello/(?P<first>[^/]+)/(?P<last>[^/]+)/?');
    }

    function it_should_be_made_regex_for_splitted_rules()
    {
        $matches = array(
            ':first',
            'first'
        );
        $this -> matchesRegexMaker($matches)
              -> shouldBe('(?P<first>[^/]+)');
    }

    function it_should_get_as_params()
    {
        $rule = "/hello/:first/:last";
        $this -> rule($rule)
              -> matches('/hello/1/2')->shouldbe(true);
        $this -> param('first') -> shouldBe('1');
        $this -> param('last')  -> shouldBe('2');

        $this -> matches('/hello/aaa/bbb')->shouldbe(true);
        $this -> param('first') -> shouldBe('aaa');
        $this -> param('last')  -> shouldBe('bbb');

        $this -> matches('/hello/aaa/bbb/')->shouldbe(true);
        $this -> param('first') -> shouldBe('aaa');
        $this -> param('last')  -> shouldBe('bbb');

        $this -> matches('/hello/%E3%82%84%E3%81%BE%E3%81%8D/%E6%97%85%E9%A4%A8')->shouldbe(true);
        $this -> param('first') -> shouldBe('やまき');
        $this -> param('last')  -> shouldBe('旅館');

        $this -> matches('/hello/aaa/bbb')->shouldbe(true);
        $this -> param('ccc') -> shouldBe(null);

        $this -> matches('/hello/aaa.m=mval.c=cval/t=tval.u=uval.bbb')->shouldbe(true);
        $this -> param('first') -> shouldBe(array(
            'aaa',
            'm' => 'mval',
            'c' => 'cval'
        ));
        $this -> param('last') -> shouldBe(array(
            't' => 'tval',
            'u' => 'uval',
            'bbb'
        ));
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

}
