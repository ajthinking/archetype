<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can retrieve class extends', function() {
	PHPFile::load('app/Models/User.php')
		->assertExtends('Authenticatable');
});

it('can attempt to retrieve class extends when it is missing', function() {
	PHPFile::make()->class()
		->assertExtends(null);
});

it('can attempt to retrieve class extends on a non class', function() {
	PHPFile::make()->file()
		->assertExtends(null);
});

it('can set class extends on a loaded file', function() {
	PHPFile::load('app/Models/User.php')
		->extends("My\BaseClass")
		->assertExtends("My\BaseClass")
		->assertValidPhp()
		->assertBeautifulPhp();
});
    

it('can set class extends on a loaded file when its empty', function() {
	PHPFile::load('app/Http/Middleware/RedirectIfAuthenticated.php')
		->extends("My\BaseClass")
		->assertExtends("My\BaseClass")
		->assertValidPhp()
		->assertBeautifulPhp();
});

it('can set class extends on a newly created class', function() {
	PHPFile::make()->class()
		->extends("Epicness")
		->assertExtends("Epicness")
		->assertValidPhp()
		->assertBeautifulPhp();
});