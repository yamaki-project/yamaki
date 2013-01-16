<?php

namespace spec\Yamaki;

use PHPSpec2\ObjectBehavior;

class Input extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Yamaki\Input');
    }

    function it_should_be_singleton()
    {
        $instance = $this->generate();
        $instance -> shouldHaveType('Yamaki\Input');
        $this->generate() -> shouldBe($instance);
    }

    function it_should_have_members()
    {
        $this->request()->shouldHaveType('Yamaki\Input\Request');
        $this->server()->shouldHaveType('Yamaki\Input\Server');
        $this->client()->shouldHaveType('Yamaki\Input\Client');
    }
}
