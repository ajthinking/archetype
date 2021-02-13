<?php

namespace Archetype\Support\AST\Visitors;

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\BuilderFactory;
use PhpParser\NodeTraverser;

class NodeReplacer extends NodeVisitorAbstract
{
    public function __construct($id, $newNode)
    {
        $this->id = $id;
        $this->newNode = $newNode;
    }

    public function leaveNode(Node $node)
    {
        return $node->__object_hash == $this->id ? $this->newNode : $node;
    }

    public function afterTraverse(array $nodes)
    {
        //
    }
    
    public static function replace($id, $newNode, $ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static($id, $newNode));
        return $traverser->traverse($ast);
    }
}
