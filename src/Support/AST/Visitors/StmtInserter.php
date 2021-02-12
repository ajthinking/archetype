<?php

namespace Archetype\Support\AST\Visitors;

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\BuilderFactory;
use PhpParser\NodeTraverser;

class StmtInserter extends NodeVisitorAbstract
{
    protected $finished = false;

    const priority = [
        'PhpParser\Node\Stmt\Namespace_',
        'PhpParser\Node\Stmt\TraitUse',
        'PhpParser\Node\Stmt\Property',
        'PhpParser\Node\Stmt\ClassMethod',
    ];

    public function __construct($id, $newNode)
    {
        $this->id = $id;
        $this->newNode = $newNode;
    }

    public function leaveNode(Node $node)
    {
        if ($this->finished) {
            return NodeTraverser::STOP_TRAVERSAL;
        }
        
        if (!$this->isTarget($node)) {
            return $node;
        }

        $node->stmts = $this->insertAndSortNodes($node->stmts);

        $this->finished = true;

        return $node;
    }

    public function beforeTraverse(array $nodes)
    {
        if ($this->id) {
            return;
        }
        
        $nodes = $this->insertAndSortNodes($nodes);

        $this->finished = true;

        return $nodes;
    }

    public function afterTraverse(array $nodes)
    {
        //
    }
    
    public static function insertStmt($id, $newNode, $ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static($id, $newNode));
        return $traverser->traverse($ast);
    }

    protected function isTarget($node)
    {
        return isset($node->__object_hash) &&  $node->__object_hash == $this->id;
    }

    protected function priority($node)
    {
        $class = get_class($node);
        
        $priority = array_search($class, $this::priority);
        return $priority !== false ? $priority : sizeof($this::priority) + 1;
    }

    protected function insertAndSortNodes($stmts)
    {
        $this->position = sizeof($stmts);

        collect($stmts)->first(function ($stmt, $index) {
            $candidatePriority = $this->priority($stmt);
            $newNodePritority = $this->priority($this->newNode);
            if ($candidatePriority >= $newNodePritority) {
                $this->position = $index;
                return true;
            }
        });
        
        $p1 = collect($stmts)->splice(0, $this->position);
        $p2 = collect([$this->newNode]);
        $p3 = collect($stmts)->splice($this->position);
        
        return $p1->concat($p2)->concat($p3)->toArray();
    }
}
