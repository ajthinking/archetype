<?php

use Archetype\Facades\PHPFile;
use function PHPUnit\Framework\assertTrue;

test('a file has an input path', function() {
	// relative
	$file = PHPFile::load('app/Models/User.php');
	assertTrue(
		$file->inputDriver()->absolutePath() === base_path('app/Models/User.php')
	);

	// absolute
	$path = base_path('app/Models/User.php');
	$file = PHPFile::load($path);
	assertTrue(
		$file->inputDriver()->absolutePath() === base_path('app/Models/User.php')
	);
});
    
test('a file has a filename', function() {
	// relative
	$file = PHPFile::load('app/Models/User.php');
	assertTrue(
		$file->inputDriver()->filename() == 'User'
	);

	// absolute
	$path = base_path('app/Models/User.php');
	$file = PHPFile::load($path);
	assertTrue(
		$file->inputDriver()->filename() === 'User'
	);
});
    
test('files created with fromString must be explicitly named', function() {
	PHPFile::fromString('<?php')->save();
})->throws(TypeError::class);