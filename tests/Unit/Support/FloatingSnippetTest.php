<?php

use Archetype\Support\Snippet;
use Archetype\Support\AST\Visitors\FormattingRemover;

it('can_create_a_snippet_without_position_attributes', function() {
	$fromSnippet = Snippet::___HAS_MANY_METHOD___();
	
	$fromSnippet = FormattingRemover::on($fromSnippet);
	
	$disabled = [
		'startLine',
		'startTokenPos',
		'endLine',
		'endTokenPos',
	];

	foreach ($disabled as $key) {
		$this->assertEquals(
			-1,
			$fromSnippet->getAttribute($key)
		);
	}
});
