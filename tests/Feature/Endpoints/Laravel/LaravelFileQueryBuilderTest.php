<?php

use Archetype\Facades\LaravelFile;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertTrue;

it('can scope on models', function() {
	assertCount(
		1,
		LaravelFile::models()->get()
	);
});

it('can scope on controllers', function() {
	assertCount(
		1,
		LaravelFile::controllers()->get()
	);
});

it('can get user', function() {
	assertTrue(
		get_class(LaravelFile::load('app/Models/User.php')) === 'Archetype\LaravelFile'
	);
});