<?php

use Archetype\Facades\LaravelFile;

test('no duplicated endpoints', function() {
	$endpoints = LaravelFile::endpointProviders()
		->map(fn($provider) => (new $provider())->getEndpoints())
		->flatten();

	$this->assertEquals(
		$endpoints->count(),
		$endpoints->unique()->count()
	);
});