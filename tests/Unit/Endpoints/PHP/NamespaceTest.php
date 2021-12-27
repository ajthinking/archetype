<?php

use Archetype\Facades\PHPFile;

it('can_retrieve_namespace', function() {
	// on a file with namespace
	$this->assertTrue(
		PHPFile::load('app/Models/User.php')->namespace() === 'App\Models'
	);

	// on a file without namespace
	$this->assertTrue(
		PHPFile::load('public/index.php')->namespace() === null
	);
});

it('can_set_namespace', function() {
	// on a file with namespace
	$this->assertTrue(
		PHPFile::load('app/Models/User.php')->namespace('New\Namespace')->namespace() === 'New\Namespace'
	);

	// on a file without namespace
	$this->assertTrue(
		PHPFile::load('public/index.php')->namespace('New\Namespace')->namespace() === 'New\Namespace'
	);
});
    
it('can_remove_namespace', function() {
	// on a file with namespace
	$this->assertTrue(
		PHPFile::load('app/Models/User.php')->remove()->namespace()->namespace() === null
	);

	// on a file without namespace
	$this->assertTrue(
		PHPFile::load('public/index.php')->remove()->namespace()->namespace() === null
	);
});