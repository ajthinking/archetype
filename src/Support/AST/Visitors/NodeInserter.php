<?php

namespace Archetype\Support\AST\Visitors;

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\BuilderFactory;
use PhpParser\NodeTraverser;

class NodeInserter extends NodeVisitorAbstract
{
    
    public function __construct($id, $newNode)
    {
        $this->id = $id;
        $this->newNode = $newNode;
    }

    public function leaveNode(Node $node)
    {
        return $node->__object_hash == $this->id ? [$this->newNode, $node] : $node;
    }

    public function beforeTraverse(array $nodes)
    {
        //
    }

    public function afterTraverse(array $nodes)
    {
        if ($this->id) {
            return $nodes;
        }

        array_push($nodes, $this->newNode);
        
        return $nodes;
    }
    
    public static function push($id, $newNode, $ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static(
            null, // will push if no ID is provided
            $newNode
        ));
        return $traverser->traverse($ast);
    }
}
