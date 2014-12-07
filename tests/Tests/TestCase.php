<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use Mockery as m;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Reset the test case to its base level test status, clearing mocks.
     */
    public function tearDown()
	{
		m::close();
	}

	protected function getPackageProviders()
	{
		return [
            'Tectonic\Shift\ShiftServiceProvider'
        ];
	}

	/**
	 * Define environment setup.
	 *
	 * @param  Illuminate\Foundation\Application $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
		// reset base path to point to our package's src directory
		$app['path.base'] = __DIR__ . '/../../';
        $app['config']->set('app.debug', false);
	}

    protected function getPackageAliases()
    {
        return [
            'Validator' => 'Illuminate\Support\Facades\Validator'
        ];
    }

    /**
     * Called by setUp before every test. Good for setting up dependencies and test conditions.
     */
    protected function init()
    {
        // Implement in child classes, instead of setUp
    }
}
