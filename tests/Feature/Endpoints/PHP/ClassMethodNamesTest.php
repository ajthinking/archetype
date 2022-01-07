<?php

use Archetype\Facades\PHPFile;

it('can retrieve class method names', function() {
	$file = PHPFile::load('app/Console/Kernel.php');

	$this->assertTrue(
		$file->methodNames() === ['schedule', 'commands']
	);
});
