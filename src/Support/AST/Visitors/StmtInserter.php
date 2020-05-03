<?php

namespace PHPFileManipulator\Support\AST\Visitors;

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\BuilderFactory;
use PhpParser\NodeTraverser;

class StmtInserter extends NodeVisitorAbstract {
    const priority = [
        'PhpParser\Node\Stmt\TraitUse',
        'PhpParser\Node\Stmt\Property',
    ];

    public function __construct($id, $newNode)
    {
        $this->id = $id;
        $this->newNode = $newNode;
    }

    public function leaveNode(Node $node) {  
        if($node->__object_hash != $this->id) return $node;

        $this->position = 0;
        $priority = 1; //$this->priority($this->newNode);
        $indexes = [];
        collect($node->stmts)->first(function($stmt, $index) use($priority) {
            $candidatePriority = $this->priority($stmt);
            if($candidatePriority >= $priority) {
                $this->position = $index;
                return true;
            }
        });
        
        $p1 = collect($node->stmts)->splice(0, $this->position);
        $p2 = collect([$this->newNode]);
        $p3 = collect($node->stmts)->splice($this->position);
        
        $node->stmts = $p1->concat($p2)->concat($p3)->toArray();

        return $node;
    }

    protected function priority($node)
    {
        $class = get_class($node);
        
        $priority = array_search($class, $this::priority);
        return $priority !== false ? $priority : sizeof($this::priority) + 1;
    }

    // public function leaveNode(Node $node) {
    //     //
    // }

    public function beforeTraverse(array $nodes) {
        //
    }

    public function afterTraverse(array $nodes) {
        //
    }
    
    public static function push($id, $newNode, $ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static(
            null, // will push if no ID is provided
            $newNode));
        return $traverser->traverse($ast);
    }
    
    public static function insertStmt($id, $newNode, $ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static($id, $newNode));
        return $traverser->traverse($ast);
    }
}