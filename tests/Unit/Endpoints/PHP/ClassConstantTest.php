<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can get a class constant', function() {
	PHPFile::load('app/Providers/RouteServiceProvider.php')
		->assertClassConstant('HOME', '/home');
});

it('can update existing class constants', function() {
	PHPFile::load('app/Providers/RouteServiceProvider.php')
		->classConstant('HOME', '/new_home')
		->assertClassConstant('HOME', '/new_home');
});

it('can create a new class constant', function() {
	PHPFile::load('app/Models/User.php')
		->classConstant('BRAND_NEW', 'it will work')
		->assertClassConstant('BRAND_NEW', 'it will work');
});

it('can remove an existing class constant', function() {
	PHPFile::make()->class('Dummy')
		->classConstant('MSG', 'hi')
		->remove()->classConstant('MSG')
		->assertNoClassConstant('MSG');		
});
