<?php

namespace PHPFileManipulator\Support\AST;

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\BuilderFactory;

class NodeReplacer extends NodeVisitorAbstract {
    public function __construct($manipulations)
    {
        $this->manipulations = $manipulations;
    }

    public function leaveNode(Node $node) {
        $id = spl_object_hash($node);
        return isset($this->manipulatons[$id]) ? null : $node;
    }

    public function afterTraverse(array $nodes) {
        //
    }    
}