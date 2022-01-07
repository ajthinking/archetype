<?php

use Archetype\Facades\PHPFile;

it('can retrieve class extends', function() {
	$file = PHPFile::load('app/Models/User.php');

	$this->assertTrue(
		$file->extends() === 'Authenticatable'
	);
});

it('can set class extends', function() {
	$file = PHPFile::load('app/Models/User.php')->extends("My\BaseClass");

	$this->assertTrue(
		$file->extends() === "My\BaseClass"
	);
});
    

it('can set class extends when its empty', function() {
	$file = PHPFile::load('app/Http/Middleware/RedirectIfAuthenticated.php')->extends("My\BaseClass");

	$this->assertTrue(
		$file->extends() === "My\BaseClass"
	);
});