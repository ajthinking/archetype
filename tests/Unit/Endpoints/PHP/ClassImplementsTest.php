<?php

use Archetype\Facades\PHPFile;

it('can_retrieve_class_implements', function() {
	$file = PHPFile::load('app/Models/User.php');

	$this->assertTrue(
		$file->implements() === []
	);
});

it('can_set_class_implements', function() {
	$file = PHPFile::load('app/Models/User.php')->implements([
	"MyInterface"
	]);

	$this->assertTrue(
		$file->implements() === [
			"MyInterface"
		]
	);
});

it('can_add_class_implements', function() {
	$file = PHPFile::load('app/Models/User.php')
		->add()->implements(['MyFirstInterface'])
		->add()->implements(['MySecondInterface']);

	$this->assertTrue(
		$file->implements() === [
			'MyFirstInterface',
			'MySecondInterface'
		]
	);
});
