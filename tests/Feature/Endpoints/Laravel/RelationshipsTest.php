<?php

use Archetype\Facades\LaravelFile;

use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertCount;

it('can insert belongs to methods', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->belongsTo(['App\Department']);

	assertContains(
		'department',
		$file->methodNames()
	);
});

it('can insert belongs to many methods', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->belongsToMany(['App\Visit', 'App\\Purchase']);

	assertContains(
		'visits',
		$file->methodNames()
	);
});
    
it('can also use string as argument', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->belongsToMany('App\Visit');

	assertContains(
		'visits',
		$file->methodNames()
	);
});
    
it('can insert HAS_MANY_METHODs', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->hasMany(['App\Gun', 'App\Rose']);

	assertContains(
		'guns',
		$file->methodNames()
	);

	assertContains(
		'roses',
		$file->methodNames()
	);
});
    
it('can insert has one methods', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->hasOne(['App\Phone']);

	assertContains(
		'phone',
		$file->methodNames()
	);
});

it('wont overwrite already existing method', function () {
	$file = LaravelFile::load('app/Models/User.php')
		->hasOne(['App\Phone'])
		->hasOne(['App\Phone']);

	assertCount(
		2,
		$file->methodNames()
	);
});