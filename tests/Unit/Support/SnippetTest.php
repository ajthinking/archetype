<?php

use Archetype\Facades\LaravelFile;
use PhpParser\Node\Stmt\ClassMethod;
use Archetype\Support\Snippet;

it('can_load_class_methods_from_snippet_defaults', function() {
	$this->assertInstanceOf(
		ClassMethod::class,
		Snippet::___HAS_MANY_METHOD___()
	);
});

it('can_replace_snippet_names', function() {
	$method = Snippet::___HAS_MANY_METHOD___([
		'___HAS_MANY_METHOD___' => 'guitars'
	]);

	$this->assertEquals(
		LaravelFile::load('app/Models/User.php')->astQuery()
			->class()
			->insertStmt($method)
			->commit()
			->end()
			->methodNames(),
		['guitars']
	);
});

it('cant_load_non_existing_snippets_from_defaults', function() {
	$this->assertNull(
		Snippet::NoSUchSnippet()
	);
});