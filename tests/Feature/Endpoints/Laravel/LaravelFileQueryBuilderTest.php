<?php

use Archetype\Facades\LaravelFile;

it('can scope on models', function() {
	$this->assertCount(
		1,
		LaravelFile::models()->get()
	);
});

it('can scope on controllers', function() {
	$this->assertCount(
		1,
		LaravelFile::controllers()->get()
	);
});

it('can get user', function() {
	$this->assertTrue(
		get_class(LaravelFile::load('app/Models/User.php')) === 'Archetype\LaravelFile'
	);
});