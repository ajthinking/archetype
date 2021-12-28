<?php

use Archetype\Facades\LaravelFile;
use Archetype\Facades\PHPFile;

test('arrays are beutiful when loaded and rendered', function() {
	$output = LaravelFile::user()->render();
	$this->assertMultilineArray('fillable', $output);
});

test('arrays are beutiful when loaded modified and rendered', function() {
	$output = LaravelFile::user()
		->add('also')->to()->property('fillable')
		->render();

	$this->assertMultilineArray('fillable', $output);
});

test('arrays are beautiful when created and rendered', function() {
	$output = PHPFile::class('FillableClass')
		->add()->property('fillable', ['first', 'second', 'third'])
		->render();

	$this->assertMultilineArray('fillable', $output);
});

test('arrays are beutiful when empty', function() {
	$output = PHPFile::class('FillableClass')
		->property('fillable', [])
		->render();
	
	$this->assertSingleLineEmptyArray('fillable', $output);
});