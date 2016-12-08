<?php

namespace spec\Main\Core;

use Main\Core\Controller;
use PhpSpec\ObjectBehavior;

class ControllerProviderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Controller::class);
    }
/*
    public function it_shoud_be_a_valid_controller()
    {
        $this->beAnInstanceOf('OnyxERP\Core\Application\ControllerProviderAbstract');
    }
 */
}
