<?php

namespace spec;

use PHPSpec2\ObjectBehavior;

class Route extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('\Yamaki\Route');
    }
}
