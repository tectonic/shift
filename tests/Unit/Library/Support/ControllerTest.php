<?php namespace Tests\Unit\Library\Support;

use Input;
use Mockery as m;
use Response;
use Tests\TestCase;
use Tests\Stubs\ControllerStub;

class ControllerTest extends TestCase
{

    protected $controller,
        $mockService,
        $mockInput;

    public function setUp()
    {
        parent::setUp();

        $this->mockService  = m::mock('MockService');
        $this->mockInput    = m::mock('Illuminate\Http\Request');

        Input::swap($this->mockInput);

        $this->controller = new ControllerStub($this->mockService);
    }

    public function testGetIndex()
    {
        $searchMock = m::mock('searchclass');
        $searchMock->shouldReceive('fromInput')->with(['param' => 'value'])->andReturn('search results');

        $this->app->instance('Tests\Stubs\SearchStub', $searchMock);

        $this->mockInput->shouldReceive('input')->andReturn(['param' => 'value']);
    }

    public function testPostStore()
    {
        $params = ['param' > 'value'];

        $this->mockInput->shouldReceive('input')->andReturn($params);

        $this->mockService->shouldReceive('create')->once()->with($params);

        $this->controller->postStore();
    }

    public function testPutUpdate()
    {
        $id     = 1;
        $params = ['param' > 'value'];

        $this->mockInput->shouldReceive('input')->andReturn($params);

        $this->mockService->shouldReceive('update')->once()->with($id, $params);

        $this->controller->putUpdate($id);
    }

    public function testDeleteDestroy()
    {
        $id = 1;

        $this->mockService->shouldReceive('delete')->once()->with($id);

        $this->controller->deleteDestroy($id);
    }
}
