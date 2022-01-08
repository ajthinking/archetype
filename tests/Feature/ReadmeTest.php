<?php

use Archetype\Tests\Support\TestableMarkdown;

test('readme examples are valid', function ($example) {
    $example->assertCodeReturnsOutput();
})->with(TestableMarkdown::make(__DIR__.'/../../readme.md')->examples);

test('docs examples are valid', function ($example) {
    $example->assertCodeReturnsOutput();
})->with(
	TestableMarkdown::make(__DIR__.'/../../docs.md')->examples
);