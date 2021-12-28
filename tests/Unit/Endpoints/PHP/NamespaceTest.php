<?php

use Archetype\Facades\PHPFile;

it('can retrieve namespace', function() {
	// on a file with namespace
	$this->assertTrue(
		PHPFile::load('app/Models/User.php')->namespace() === 'App\Models'
	);

	// on a file without namespace
	$this->assertTrue(
		PHPFile::load('public/index.php')->namespace() === null
	);
});

it('can set namespace', function() {
	// on a file with namespace
	$this->assertTrue(
		PHPFile::load('app/Models/User.php')->namespace('New\Namespace')->namespace() === 'New\Namespace'
	);

	// on a file without namespace
	$this->assertTrue(
		PHPFile::load('public/index.php')->namespace('New\Namespace')->namespace() === 'New\Namespace'
	);
});
    
it('can remove namespace', function() {
	// on a file with namespace
	$this->assertTrue(
		PHPFile::load('app/Models/User.php')->remove()->namespace()->namespace() === null
	);

	// on a file without namespace
	$this->assertTrue(
		PHPFile::load('public/index.php')->remove()->namespace()->namespace() === null
	);
});