<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can get a class constant', function() {
	PHPFile::load('app/Providers/RouteServiceProvider.php')
		->assertClassConstant('HOME', '/home');
});

it('can attempt to get a class constant on an empty file', function() {
	PHPFile::make()->file()->assertNoClassConstants();
});

it('can create new class constants', function() {
	PHPFile::make()->class()
		->classConstant('C1', 1)
		->classConstant('C2', 2)
		->classConstant('C3', 3)
		->assertValidPhp()
		->assertBeautifulPhp()		
		->assertClassConstant('C1', 1)
		->assertClassConstant('C2', 2)
		->assertClassConstant('C3', 3);
});

it('can update existing class constants', function() {
	PHPFile::load('app/Providers/RouteServiceProvider.php')
		->classConstant('HOME', '/new_home')
		->assertValidPhp()
		->assertBeautifulPhp()
		->assertClassConstant('HOME', '/new_home');
});

it('can create a new class constant in an existing file'/*, function() {
	PHPFile::load('app/Models/User.php')
		->classConstant('BRAND_NEW', 42)
		->assertValidPhp()
		->assertBeautifulPhp()
		->assertClassConstant('BRAND_NEW', 42);
}*/);

it('can remove an existing class constant in a new file', function() {
	PHPFile::make()->class(\App\Dummy::class)
		->classConstant('MSG', 'hi')
		->remove()->classConstant('MSG')
		->assertValidPhp()		
		->assertNoClassConstant('MSG')
		->assertBeautifulPhp();
});

it('can remove an existing class constant in a loaded file', function() {
	PHPFile::load('app/Providers/RouteServiceProvider.php')
		->remove()->classConstant('HOME')
		->assertValidPhp()		
		->assertNoClassConstant('HOME')
		->assertBeautifulPhp();
});