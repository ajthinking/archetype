<?php

use Archetype\Facades\LaravelFile;
use PhpParser\Node\Stmt\ClassMethod;
use Archetype\Support\Snippet;

it('can load class methods from snippet defaults', function() {
	$this->assertInstanceOf(
		ClassMethod::class,
		Snippet::___HAS_MANY_METHOD___()
	);
});

it('can replace snippet names', function() {
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

it('cant load non existing snippets from defaults', function() {
	$this->assertNull(
		Snippet::NoSUchSnippet()
	);
});