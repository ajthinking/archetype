<?php

use Archetype\Facades\LaravelFile;

it('can_insert_belongs_to_methods', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->belongsTo(['App\Department']);

	$this->assertContains(
		'department',
		$file->methodNames()
	);
});

it('can_insert_belongs_to_many_methods', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->belongsToMany(['App\Visit', 'App\\Purchase']);

	$this->assertContains(
		'visits',
		$file->methodNames()
	);
});
    
it('can_also_use_string_as_argument', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->belongsToMany('App\Visit');

	$this->assertContains(
		'visits',
		$file->methodNames()
	);
});
    
it('can_insert_has_many_methods', function () {
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
    
it('can_insert_has_one_methods', function () {
	$file = LaravelFile::load('app/Models/User.php');
	$file->hasOne(['App\Phone']);

	$this->assertContains(
		'phone',
		$file->methodNames()
	);
});

it('wont_overwrite_already_existing_method', function () {
	$file = LaravelFile::load('app/Models/User.php')
		->hasOne(['App\Phone'])
		->hasOne(['App\Phone']);

	$this->assertCount(
		2,
		$file->methodNames()
	);
});