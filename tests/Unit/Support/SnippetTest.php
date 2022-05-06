<?php

use Archetype\Facades\LaravelFile;
use Archetype\Support\AST\Visitors\FormattingRemover;
use PhpParser\Node\Stmt\ClassMethod;
use Archetype\Support\Snippet;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNull;

it('can load class methods from snippet defaults', function() {
	assertInstanceOf(
		ClassMethod::class,
		Snippet::___HAS_MANY_METHOD___()
	);
});

it('can replace snippet names', function() {
	$method = Snippet::___HAS_MANY_METHOD___([
		'___HAS_MANY_METHOD___' => 'guitars'
	]);

	assertEquals(
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
	assertNull(
		Snippet::NoSuchSnippet()
	);
});

it('can create a snippet without position attributes', function() {
	$fromSnippet = Snippet::___HAS_MANY_METHOD___();
	
	$fromSnippet = FormattingRemover::on($fromSnippet);
	
	$disabled = [
		'startLine',
		'startTokenPos',
		'endLine',
		'endTokenPos',
	];

	foreach ($disabled as $key) {
		assertEquals(-1, $fromSnippet->getAttribute($key));
	}
});