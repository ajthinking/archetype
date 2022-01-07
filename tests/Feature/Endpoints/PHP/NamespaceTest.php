<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can retrieve namespace', function() {
	PHPFile::load('app/Models/User.php')
		->assertNamespace('App\Models');
});

it('can attempt to retrieve namespace', function() {
	PHPFile::load('public/index.php')
		->assertNamespace(null);
});

it('can update namespace', function() {
	PHPFile::load('app/Models/User.php')
		->namespace('New\Namespace')
		->assertNamespace('New\Namespace');
});

it('can give a file a namespace', function() {
	PHPFile::load('public/index.php')
		->namespace('New\Namespace')
		->assertNamespace('New\Namespace')
		->assertValidPhp()
		->assertBeautifulPhp();
});
    
it('can remove namespace', function() {
	PHPFile::load('app/Models/User.php')
		->remove()->namespace()
		->assertNamespace(null);
});

it('can attempt to remove namespace ', function() {
	PHPFile::load('public/index.php')
		->remove()->namespace()
		->assertNamespace(null);
});