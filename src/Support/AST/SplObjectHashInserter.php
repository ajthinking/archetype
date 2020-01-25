<?php

namespace PHPFileManipulator\Support\AST;

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\BuilderFactory;

class SplObjectHashInserter extends NodeVisitorAbstract {

    public function leaveNode(Node $node) {
        $node->spl_object_hash = spl_object_hash($node);
        return $node;
    }
}