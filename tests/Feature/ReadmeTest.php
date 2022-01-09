<?php

use Archetype\Tests\Support\TestableMarkdown;

test('readme examples are valid', function ($heading, $example) {
    $example->assertCodeReturnsOutput();
})->with(
	TestableMarkdown::make(__DIR__.'/../../readme.md')
		->toPestTestArray()
);

test('docs examples are valid', function ($heading, $example) {
    $example->assertValid();
})->with(
	TestableMarkdown::make(__DIR__.'/../../docs.md')
		->toPestTestArray()
);