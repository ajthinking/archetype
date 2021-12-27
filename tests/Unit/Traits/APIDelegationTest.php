<?php

use Archetype\Facades\PHPFile;

it('can_delegate_method_calls', function () {
	$file = PHPFile::load('app/Models/User.php');

	// Existing method on $this
	$this->assertTrue(
		$file->ast() != null
	);

	// Existing method on resource
	$this->assertTrue(
		$file->namespace() != null
	);
	
	// Non existing method are catched
	$this->expectException(BadMethodCallException::class);
	$file->this_method_does_not_exists();
});
