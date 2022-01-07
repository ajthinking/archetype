<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can retrieve class name', function() {
	PHPFile::load('app/Models/User.php')
		->assertClassName("User");
});
    
it('can retrieve full class name', function() {
	PHPFile::load('app/Models/User.php')
		->full()->assertClassName("App\Models\User");
});

it('can set class name on a loaded file with a class', function() {
	PHPFile::load('app/Models/User.php')
		->className("NewName")
		->assertClassName("NewName")
		->assertValidPhp()
		->assertBeautifulPhp();
});

it('can set class name on a created file with a class', function() {
	PHPFile::make()->class()
		->className("NewName")
		->assertClassName("NewName")
		->assertValidPhp()
		->assertBeautifulPhp();
});

it('can attempt to set class on a file without a class', function() {
	PHPFile::make()->file()
		->className("NewName")
		->assertClassName(null)
		->assertValidPhp()
		->assertBeautifulPhp();
});