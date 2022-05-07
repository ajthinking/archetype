<?php

namespace Archetype\Support\AST\Visitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeTraverser;

class FormattingRemover extends NodeVisitorAbstract
{
	final public function __construct()
	{
	}

    public function leaveNode(Node $node)
    {
        
        $node->setAttribute('startLine', -1);
        $node->setAttribute('startTokenPos', -1);
        $node->setAttribute('endLine', -1);
        $node->setAttribute('endTokenPos', -1);
        $node->setAttribute('origNode', null);

        if ($node->getAttribute('comments')) {
            $originalComments = $node->getAttribute('comments');
            $newComments = collect($originalComments)->map(function ($comment) {
                return new \PhpParser\Comment\Doc($comment->getText());
            })->toArray();

            $node->setAttribute('comments', $newComments);
        }

        return $node;
    }

    public static function on($ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static);
        return $traverser->traverse([$ast])[0];
    }
}
