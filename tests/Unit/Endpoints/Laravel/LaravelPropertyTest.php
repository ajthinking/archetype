<?php

use Archetype\Facades\LaravelFile;

it('can_retrieve_fillables', function() {
	$this->assertTrue(
		LaravelFile::load('app/Models/User.php')->fillable() == ['name', 'email', 'password',]
	);
});

it('can_retrieve_hidden', function() {
	$this->assertTrue(
		LaravelFile::load('app/Models/User.php')->hidden() == ['password', 'remember_token',]
	);
});

it('wont_break_if_properties_are_missing', function() {
	$this->assertNull(
		LaravelFile::load('public/index.php')->hidden()
	);
});

it('will_assume_array_if_we_are_inserting_on_a_new_hidden_property', function() {
	$hidden = LaravelFile::load('app/Models/User.php')
		->remove()->hidden()
		->hidden('ghost')->hidden();

	$this->assertEquals(
		['ghost'],
		$hidden
	);

	$hidden = LaravelFile::load('app/Models/User.php')
		->remove()->hidden()
		->hidden(['ghost'])->hidden();

	$this->assertEquals(
		['ghost'],
		$hidden
	);
});

it('can_set_fillables', function() {
	$this->assertEquals(
		LaravelFile::load('app/Models/User.php')->fillable(['guns', 'roses'])->fillable(),
		['guns', 'roses',]
	);
});

it('can_add_fillables', function() {
	$this->assertEquals(
		LaravelFile::load('app/Models/User.php')
			->fillable(['guns', 'roses'])
			->add()->fillable(['metallica'])
			->fillable(),
		['guns', 'roses', 'metallica']
	);
});

it('can_set_hidden', function() {
	$this->assertEquals(
		LaravelFile::load('app/Models/User.php')->hidden(['metallica', 'ozzy'])->hidden(),
		['metallica', 'ozzy',]
	);
});

it('can_use_setter_on_associative_arrays', function() {
	$output = LaravelFile::load('app/Models/User.php')
		->casts(['free' => 'bird'])
		->casts();

	$this->assertEquals([
		'free' => 'bird',
	], $output);
});

it('can_add_to_associative_arrays', function() {
	$output = LaravelFile::load('app/Models/User.php')
		->add()->casts(['free' => 'bird'])
		->casts();

	$this->assertEquals([
		'email_verified_at' => 'datetime',
		'free' => 'bird',
	], $output);
});

it('can_empty_associative_arrays', function() {
	$output = LaravelFile::load('app/Models/User.php')
		->empty()->casts()
		->casts();

	$this->assertEquals([], $output);
});