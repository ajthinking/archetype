<?php

namespace Archetype\Support\AST\Visitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeTraverser;

class NodePropertyReplacer extends NodeVisitorAbstract
{
	public string $id;
	public string $key;
	public $value;

    final public function __construct(string $id, string $key, $value)
    {
        $this->id = $id;
        $this->key = $key;
        $this->value = $value;
    }

    public function leaveNode(Node $node)
    {
        if ($node->__object_hash === $this->id) {
            $node->{$this->key} = $this->value;
        }

        return $node;
    }
    
    public static function replace($id, $key, $value, $ast)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new static($id, $key, $value));
        return $traverser->traverse($ast);
    }
}
