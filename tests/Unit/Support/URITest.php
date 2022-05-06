<?php

use Archetype\Support\URI;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

it('can enterpret input as path or name', function() {
	assertTrue(URI::make('')->isPath());
	assertTrue(URI::make('car')->isPath());
	assertTrue(URI::make('car.php')->isPath());
	assertTrue(URI::make('Car.php')->isPath());
	assertTrue(URI::make('app/Car')->isPath());
	assertTrue(URI::make('/Car')->isPath());
	
	assertTrue(URI::make('Car')->isName());
	assertTrue(URI::make('\\Car')->isName());
	assertTrue(URI::make('App\\Car')->isName());
	assertTrue(URI::make('\\App\\Car')->isName());
});

it('can get resolve namespace', function($uri, $expectedNamespace) {
	assertEquals($expectedNamespace, URI::make($uri)->namespace());
})->with([
	// from paths
	['app/Cool', 'App'],
	['app/Models/Also', 'App\Models'],
	['app/Models/Also.php', 'App\Models'],
	['acme/EpicTool', 'acme'], // if you expect 'Acme' - map in config

	// // from namespaced
	['App\Models\Rover', 'App\Models'],
	['Custom\Star', 'Custom'],
]);
