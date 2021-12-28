<?php

use Archetype\LaravelFile;

test('no duplicated endpoints', function() {
	$endpoints = (new LaravelFile)
		->endpointProviders()
		->map(function ($provider) {
			return (new $provider())->getEndpoints();
		})->flatten();

	$this->assertEquals(
		$endpoints->count(),
		$endpoints->unique()->count()
	);
});