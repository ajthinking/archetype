<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can retrieve class implements', function() {
	PHPFile::load('app/Models/User.php')
		->assertImplements([]);
});

it('can set class implements', function() {
	PHPFile::load('app/Models/User.php')
		->implements(['MyInterface'])
		->assertImplements(['MyInterface']);
});

it('can add class implements', function() {
	PHPFile::load('app/Models/User.php')
		->add()->implements(['MyFirstInterface'])
		->add()->implements(['MySecondInterface'])
		->assertImplements([
			'MyFirstInterface',
			'MySecondInterface'
		]);
});