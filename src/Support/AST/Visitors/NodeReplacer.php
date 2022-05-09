<?php

namespace Archetype\Support\AST\Visitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeTraverser;

class NodeReplacer extends NodeVisitorAbstract
{
	public string $id;
	public $newNode;

    final public function __construct(string $id, $newNode)
    {
        $this->id = $id;
        $this->newNode = $newNode;
    }

    public function leaveNode(Node $node)
    {
        return $node->__object_hash === $this->id ? $this->newNode : $node;
    }
    
    public static function replace($id, $newNode, $ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static($id, $newNode));
        return $traverser->traverse($ast);
    }
}
