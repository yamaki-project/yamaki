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
              -> shouldBe('/hello/(?P<first>[^/]+)/(?P<last>[^/]+)');
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
              -> matches('/hello/1/2')->shouldBe(true);
        $this -> param('first') -> shouldBe('1');
        $this -> param('last')  -> shouldBe('2');
    }

}
