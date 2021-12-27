<?php

use Archetype\Facades\LaravelFile;

it('can_scope_on_models', function() {
	$this->assertCount(
		1,
		LaravelFile::models()->get()
	);
});

it('can_scope_on_controllers', function() {
	$this->assertCount(
		1,
		LaravelFile::controllers()->get()
	);
});

it('can_get_user', function() {
	$this->assertTrue(
		get_class(LaravelFile::load('app/Models/User.php')) === 'Archetype\LaravelFile'
	);
});