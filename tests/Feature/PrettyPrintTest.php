<?php

use Archetype\Facades\LaravelFile;
use Archetype\Facades\PHPFile;

test('arrays_are_beutiful_when_loaded_and_rendered', function() {
	$output = LaravelFile::user()->render();
	$this->assertMultilineArray('fillable', $output);
});

test('arrays_are_beutiful_when_loaded_modified_and_rendered', function() {
	$output = LaravelFile::user()
		->add('also')->to()->property('fillable')
		->render();

	$this->assertMultilineArray('fillable', $output);
});

test('arrays_are_beautiful_when_created_and_rendered', function() {
	$output = PHPFile::class('FillableClass')
		->add()->property('fillable', ['first', 'second', 'third'])
		->render();

	$this->assertMultilineArray('fillable', $output);
});

test('arrays_are_beutiful_when_empty', function() {
	$output = PHPFile::class('FillableClass')
		->property('fillable', [])
		->render();
	
	$this->assertSingleLineEmptyArray('fillable', $output);
});