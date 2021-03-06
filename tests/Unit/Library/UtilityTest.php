<?php namespace Tests\Unit\Library;

use Mockery as m;
use Tectonic\Shift\Library\Utility;

class UtilityTest extends \Tests\UnitTestCase
{
	public function setUp()
	{
		$this->request = m::mock('Illuminate\Http\Request');
		$this->view = m::mock('Illuminate\View\Environment');

		$this->utility = new Utility($this->request, $this->view);
	}

	public function tearDown()
	{
		m::close();
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Utility::eventName expects at least 2 parameters (only 1 provided). The first parameter should be the domain of the event, with extra arguments being used to craft the details of the event name.
	 */
	public function testEventNameShouldThrowExceptionWithTooFewArgs()
	{
		$this->utility->eventName('not enough args');
	}

	public function testEventNameCalledWithTwoArgsShouldReturnExactlyTwoParts()
	{
		$this->assertEquals('shift::loading', $this->utility->eventName('shift', 'loading'));
	}

	public function testEventNameCalledWithMoreThanTwoArgsShouldBuildTheStringWithDecimalsAfterTheFirst()
	{
		$this->assertEquals('shift::run.some.long.event.name', $this->utility->eventName('shift', 'run', 'some', 'long', 'event', 'name'));
	}
}
