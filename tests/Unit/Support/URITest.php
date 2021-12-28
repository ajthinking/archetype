<?php

use Archetype\Support\URI;

it('can enterpret input as path or name', function() {
	$this->assertTrue(URI::make('')->isPath());
	$this->assertTrue(URI::make('car')->isPath());
	$this->assertTrue(URI::make('car.php')->isPath());
	$this->assertTrue(URI::make('Car.php')->isPath());
	$this->assertTrue(URI::make('app/Car')->isPath());
	$this->assertTrue(URI::make('/Car')->isPath());
	
	$this->assertTrue(URI::make('Car')->isName());
	$this->assertTrue(URI::make('\\Car')->isName());
	$this->assertTrue(URI::make('App\\Car')->isName());
	$this->assertTrue(URI::make('\\App\\Car')->isName());
});

it('can get resolve namespace', function() {
	$namespaces = [
		// from paths
		'App' => URI::make('app/Cool')->namespace(),
		'App\Models' => URI::make('app/Models/Also')->namespace(),
		'App\Models' => URI::make('app/Models/Also.php')->namespace(),
		'acme' => URI::make('acme/EpicTool')->namespace(), // if you expect 'Acme' - map in config

		// from namespaced
		'App\Models' => URI::make('App\Models\Moon')->namespace(),
		'Custom' => URI::make('Custom\Star')->namespace(),
	];

	foreach ($namespaces as $expected => $actual) {
		$this->assertEquals($expected, $actual);
	}
});
