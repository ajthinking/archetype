<?php

namespace Archetype\Support\AST\Visitors;

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\BuilderFactory;
use PhpParser\NodeTraverser;

class HashInserter extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        $node->__object_hash = spl_object_hash($node);
        return $node;
    }

    public static function on($ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static);
        return $traverser->traverse($ast);
    }
}
