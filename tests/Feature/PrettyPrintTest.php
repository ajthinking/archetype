<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

test('arrays are beutiful when loaded and rendered', function() {
	PHPFile::load('app/Models/User.php')
		->assertMultilineArray('fillable');
});

test('arrays are beutiful when loaded modified and rendered', function() {
	PHPFile::load('app/Models/User.php')
		->add('also')->to()->property('fillable')
		->assertMultilineArray('fillable');
});

test('arrays are beautiful when created and rendered', function() {
	PHPFile::make()->class('CountClass')
		->add()->property('counts', ['first', 'second', 'third'])
		->assertMultilineArray('counts');
});

test('arrays are beutiful when empty', function() {
	PHPFile::class('FillableClass')
		->property('fillable', [])
		->assertSingleLineEmptyArray('fillable');
});
