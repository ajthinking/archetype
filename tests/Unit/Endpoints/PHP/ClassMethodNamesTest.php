<?php

use Archetype\Facades\PHPFile;

it('can_retrieve_class_method_names', function() {
	$file = PHPFile::load('app/Console/Kernel.php');

	$this->assertTrue(
		$file->methodNames() === ['schedule', 'commands']
	);
});
