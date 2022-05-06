<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;
use function PHPUnit\Framework\assertEquals;

it('it can make an empty file', function () {
	PHPFile::make()->file()
		->assertValidPhp();
});

it('it cannot make a class file without a namespace', function () {
	PHPFile::make()->class(\Dummy::class);
})->throws(Exception::class);

test('make file defaults to root', function () {
	$output = PHPFile::make()->file('script.php')->outputDriver();
	assertEquals('', $output->relativeDir);
	assertEquals('script', $output->filename);
	assertEquals('php', $output->extension);
});

test('the php file maker can write into directories', function () {
	$output = PHPFile::make()->file('app/HTTP/script.php')->outputDriver();
	assertEquals('app/HTTP', $output->relativeDir);
	assertEquals('script', $output->filename);
	assertEquals('php', $output->extension);
});

it('can give a full path'/*, function () {
	$output = PHPFile::make()->class(base_path('app/Scripter.php'))->outputDriver();
	assertEquals('app', $output->relativeDir);
	assertEquals('Scripter', $output->filename);
	assertEquals('php', $output->extension);
}*/);
    
it('the php class maker accepts a namespaced class', function () {
	$file = PHPFile::make()->class('Weapons\RocketLauncher');
	
	$output = $file->outputDriver();

	assertEquals('Weapons', $output->relativeDir);
	assertEquals('RocketLauncher', $output->filename);
	assertEquals('php', $output->extension);
});
