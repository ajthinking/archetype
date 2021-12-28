<?php

use Archetype\Facades\PHPFile;

test('a file has an input path', function() {
	// relative
	$file = PHPFile::load('app/Models/User.php');
	$this->assertTrue(
		$file->inputDriver()->absolutePath() == base_path('app/Models/User.php')
	);

	// absolute
	$path = base_path('app/Models/User.php');
	$file = PHPFile::load($path);
	$this->assertTrue(
		$file->inputDriver()->absolutePath() == base_path('app/Models/User.php')
	);
});
    
test('a file has a filename', function() {
	// relative
	$file = PHPFile::load('app/Models/User.php');
	$this->assertTrue(
		$file->inputDriver()->filename() == 'User'
	);

	// absolute
	$path = base_path('app/Models/User.php');
	$file = PHPFile::load($path);
	$this->assertTrue(
		$file->inputDriver()->filename() == 'User'
	);
});
    
test('files created with fromString must be explicitly named', function() {
	$file = PHPFile::fromString('<?php');

	$this->expectException(TypeError::class);

	$file->save(); // It dont know where to save!
});