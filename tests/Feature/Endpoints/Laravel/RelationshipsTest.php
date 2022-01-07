<?php

use Archetype\Facades\LaravelFile;

it('can insert belongs to methods', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->belongsTo(['App\Department']);

	$this->assertContains(
		'department',
		$file->methodNames()
	);
});

it('can insert belongs to many methods', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->belongsToMany(['App\Visit', 'App\\Purchase']);

	$this->assertContains(
		'visits',
		$file->methodNames()
	);
});
    
it('can also use string as argument', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->belongsToMany('App\Visit');

	$this->assertContains(
		'visits',
		$file->methodNames()
	);
});
    
it('can insert HAS_MANY_METHODs', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->hasMany(['App\Gun', 'App\Rose']);

	$this->assertContains(
		'guns',
		$file->methodNames()
	);

	$this->assertContains(
		'roses',
		$file->methodNames()
	);
});
    
it('can insert has one methods', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->hasOne(['App\Phone']);

	$this->assertContains(
		'phone',
		$file->methodNames()
	);
});

it('wont overwrite already existing method', function () {
	$file = LaravelFile::load('app/Models/User.php')
		->hasOne(['App\Phone'])
		->hasOne(['App\Phone']);

	$this->assertCount(
		2,
		$file->methodNames()
	);
});