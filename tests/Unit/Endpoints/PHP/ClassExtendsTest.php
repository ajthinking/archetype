<?php

use Archetype\Facades\PHPFile;

it('can_retrieve_class_extends', function() {
	$file = PHPFile::load('app/Models/User.php');

	$this->assertTrue(
		$file->extends() === 'Authenticatable'
	);
});

it('can_set_class_extends', function() {
	$file = PHPFile::load('app/Models/User.php')->extends("My\BaseClass");

	$this->assertTrue(
		$file->extends() === "My\BaseClass"
	);
});
    

it('can_set_class_extends_when_its_empty', function() {
	$file = PHPFile::load('app/Http/Middleware/RedirectIfAuthenticated.php')->extends("My\BaseClass");

	$this->assertTrue(
		$file->extends() === "My\BaseClass"
	);
});