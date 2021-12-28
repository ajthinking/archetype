<?php

use Archetype\Facades\PHPFile;

it('it can make files with basic php templates', function () {
	$this->assertInstanceOf(
		\Archetype\PHPFile::class,
		PHPFile::make()->class('CoolClass')
	);
});

it('the php file maker defaults to root', function () {
	$output = PHPFile::make()->file('script.php')->outputDriver();
	$this->assertEquals('', $output->relativeDir);
	$this->assertEquals('script', $output->filename);
	$this->assertEquals('php', $output->extension);
});

it('the php file maker can write into directories', function () {
	$output = PHPFile::make()->file('app/HTTP/script.php')->outputDriver();
	$this->assertEquals('app/HTTP', $output->relativeDir);
	$this->assertEquals('script', $output->filename);
	$this->assertEquals('php', $output->extension);
});
    
it('the php class maker accepts a namespaced class', function () {
	$file = PHPFile::make()->class('Weapons\RocketLauncher');
	
	$output = $file->outputDriver();
	$this->assertEquals('app/Weapons', $output->relativeDir);
	$this->assertEquals('RocketLauncher', $output->filename);
	$this->assertEquals('php', $output->extension);

	$this->assertEquals('App\Weapons', $file->namespace());
	$this->assertEquals('RocketLauncher', $file->className());
});
