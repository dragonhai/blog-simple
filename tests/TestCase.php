<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {
  
  protected $baseUrl = 'http://localhost';

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

	public function __call($method, $args)
	{
		if (in_array($method, ['get', 'post', 'put', 'patch', 'delete']))
		{
			return $this->call($method, $args[0]);
		}
	 
		throw new BadMethodCallException;
	}

	public function mock($class)
	{
		$mock = Mockery::mock($class);
		$this->app->instance($class, $mock);
		return $mock;
	}

}
