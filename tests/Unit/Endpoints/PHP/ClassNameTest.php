<?php

use Archetype\Facades\PHPFile;

it('can_retrieve_class_name', function() {
	$file = PHPFile::load('app/Models/User.php');

	$this->assertTrue(
		$file->className() === "User"
	);
});
    
it('can_retrieve_full_class_name', function() {
	$file = PHPFile::load('app/Models/User.php');

	$this->assertTrue(
		$file->full()->className() === "App\Models\User"
	);
});

it('can_set_class_name', function() {
	// on a file with a class
	$this->assertTrue(
		PHPFile::load('app/Models/User.php')->className("NewName")->className() === "NewName"
	);

	// on a file without a class
	$this->assertTrue(
		PHPFile::load('public/index.php')->className("NewName")->className() === null
	);
});