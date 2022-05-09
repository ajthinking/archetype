<?php

namespace Archetype\Support\AST\Visitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeTraverser;

class NodeRemover extends NodeVisitorAbstract
{
	public string $id;

    final public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function leaveNode(Node $node)
    {
        return $node->__object_hash === $this->id ? NodeTraverser::REMOVE_NODE : $node;
    }
    
    public static function remove($id, $ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static($id));
        return $traverser->traverse($ast);
    }
}
