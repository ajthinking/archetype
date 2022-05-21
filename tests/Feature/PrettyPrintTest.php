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
	PHPFile::make()->class()
		->add()->property('counts', ['first', 'second', 'third'])
		->assertMultilineArray('counts');
});

test('class statements have linebreaks between them', function() {
	PHPFile::make()->class()
		->property('a', 1)
		->property('b', 2)
		->property('c', 3)
		->assertLinebreaksBetweenClassStmts();
});

test('created class without use statements have beautiful header', function() {
	PHPFile::make()->class()
		->assertProperSpacingInClassHeader();
});