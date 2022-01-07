<?php

use Archetype\Facades\LaravelFile;

it('can retrieve fillables', function() {
	$this->assertTrue(
		LaravelFile::load('app/Models/User.php')->fillable() == ['name', 'email', 'password',]
	);
});

it('can retrieve hidden', function() {
	$this->assertTrue(
		LaravelFile::load('app/Models/User.php')->hidden() == ['password', 'remember_token',]
	);
});

it('wont break if properties are missing', function() {
	$this->assertNull(
		LaravelFile::load('public/index.php')->hidden()
	);
});

it('will assume array if we are inserting on a new hidden property', function() {
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

it('can set fillables', function() {
	$this->assertEquals(
		LaravelFile::load('app/Models/User.php')->fillable(['guns', 'roses'])->fillable(),
		['guns', 'roses',]
	);
});

it('can add fillables', function() {
	$this->assertEquals(
		LaravelFile::load('app/Models/User.php')
			->fillable(['guns', 'roses'])
			->add()->fillable(['metallica'])
			->fillable(),
		['guns', 'roses', 'metallica']
	);
});

it('can set hidden', function() {
	$this->assertEquals(
		LaravelFile::load('app/Models/User.php')->hidden(['metallica', 'ozzy'])->hidden(),
		['metallica', 'ozzy',]
	);
});

it('can use setter on associative arrays', function() {
	$output = LaravelFile::load('app/Models/User.php')
		->casts(['free' => 'bird'])
		->casts();

	$this->assertEquals([
		'free' => 'bird',
	], $output);
});

it('can add to associative arrays', function() {
	$output = LaravelFile::load('app/Models/User.php')
		->add()->casts(['free' => 'bird'])
		->casts();

	$this->assertEquals([
		'email_verified_at' => 'datetime',
		'free' => 'bird',
	], $output);
});

it('can empty associative arrays', function() {
	$output = LaravelFile::load('app/Models/User.php')
		->empty()->casts()
		->casts();

	$this->assertEquals([], $output);
});