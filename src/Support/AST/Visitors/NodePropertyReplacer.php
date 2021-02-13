<?php

namespace Archetype\Support\AST\Visitors;

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\BuilderFactory;
use PhpParser\NodeTraverser;

class NodePropertyReplacer extends NodeVisitorAbstract
{
    public function __construct($id, $key, $value)
    {
        $this->id = $id;
        $this->key = $key;
        $this->value = $value;
    }

    public function leaveNode(Node $node)
    {
        if ($node->__object_hash == $this->id) {
            $node->{$this->key} = $this->value;
        }

        return $node;
    }

    public function afterTraverse(array $nodes)
    {
        //
    }
    
    public static function replace($id, $key, $value, $ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static($id, $key, $value));
        return $traverser->traverse($ast);
    }
}
