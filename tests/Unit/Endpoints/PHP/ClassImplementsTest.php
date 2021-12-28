<?php

use Archetype\Facades\PHPFile;

it('can retrieve class implements', function() {
	$file = PHPFile::load('app/Models/User.php');

	$this->assertTrue(
		$file->implements() === []
	);
});

it('can set class implements', function() {
	$file = PHPFile::load('app/Models/User.php')->implements([
	"MyInterface"
	]);

	$this->assertTrue(
		$file->implements() === [
			"MyInterface"
		]
	);
});

it('can add class implements', function() {
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
