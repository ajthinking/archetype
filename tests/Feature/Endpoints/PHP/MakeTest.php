<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('it can make an empty file', function () {
	PHPFile::make()->file()
		->assertValidPhp();
});

it('it cannot make a class file without a namespace', function () {
	PHPFile::make()->class(\Dummy::class);
})->throws(Exception::class);

test('make file defaults to root', function () {
	$output = PHPFile::make()->file('script.php')->outputDriver();
	$this->assertEquals('', $output->relativeDir);
	$this->assertEquals('script', $output->filename);
	$this->assertEquals('php', $output->extension);
});

test('the php file maker can write into directories', function () {
	$output = PHPFile::make()->file('app/HTTP/script.php')->outputDriver();
	$this->assertEquals('app/HTTP', $output->relativeDir);
	$this->assertEquals('script', $output->filename);
	$this->assertEquals('php', $output->extension);
});

it('can give a full path', function () {
	$this->markTestIncomplete();
	
	$output = PHPFile::make()->class(base_path('app/Scripter.php'))->outputDriver();
	$this->assertEquals('app', $output->relativeDir);
	$this->assertEquals('Scripter', $output->filename);
	$this->assertEquals('php', $output->extension);
});
    
it('the php class maker accepts a namespaced class', function () {
	$file = PHPFile::make()->class('Weapons\RocketLauncher');
	
	$output = $file->outputDriver();

	$this->assertEquals('Weapons', $output->relativeDir);
	$this->assertEquals('RocketLauncher', $output->filename);
	$this->assertEquals('php', $output->extension);
});
