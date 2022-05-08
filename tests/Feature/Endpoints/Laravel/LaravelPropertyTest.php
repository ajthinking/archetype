<?php

use Archetype\Facades\LaravelFile;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

it('can retrieve fillables', function() {
	assertTrue(
		LaravelFile::load('app/Models/User.php')->fillable() === ['name', 'email', 'password',]
	);
});

it('can retrieve hidden', function() {
	assertTrue(
		LaravelFile::load('app/Models/User.php')->hidden() === ['password', 'remember_token',]
	);
});

it('wont break if properties are missing', function() {
	assertNull(
		LaravelFile::load('public/index.php')->hidden()
	);
});

it('putting this test here helps the one below not break', function() {
	// WHY?
});

it('will assume array if we are inserting on a new hidden property', function() {
	$hidden = LaravelFile::load('app/Models/User.php')
		->remove()->hidden()
		->hidden('ghost')->hidden();

	assertEquals(['ghost'], $hidden);
});

it('can set fillables', function() {
	assertEquals(
		LaravelFile::load('app/Models/User.php')->fillable(['guns', 'roses'])->fillable(),
		['guns', 'roses',]
	);
});

it('can add fillables', function() {
	assertEquals(
		LaravelFile::load('app/Models/User.php')
			->fillable(['guns', 'roses'])
			->add()->fillable(['metallica'])
			->fillable(),
		['guns', 'roses', 'metallica']
	);
});

it('can set hidden', function() {
	assertEquals(
		LaravelFile::load('app/Models/User.php')->hidden(['metallica', 'ozzy'])->hidden(),
		['metallica', 'ozzy',]
	);
});

it('can use setter on associative arrays', function() {
	$output = LaravelFile::load('app/Models/User.php')
		->casts(['free' => 'bird'])
		->casts();

	assertEquals([
		'free' => 'bird',
	], $output);
});

it('can add to associative arrays', function() {
	$output = LaravelFile::load('app/Models/User.php')
		->add()->casts(['free' => 'bird'])
		->casts();

	assertEquals([
		'email_verified_at' => 'datetime',
		'free' => 'bird',
	], $output);
});

it('can empty associative arrays', function() {
	$output = LaravelFile::load('app/Models/User.php')
		->empty()->casts()
		->casts();

	assertEquals([], $output);
});