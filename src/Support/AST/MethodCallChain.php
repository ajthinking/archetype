<?php

namespace Archetype\Support\AST;

class MethodCallChain
{
	protected $node;

	public function __construct($node)
	{
		$this->node = $node;
	}

	public static function make($node)
	{
		return new static($node);
	}

	public function flatten()
	{
		return $this->flattenExpr($this->node, [$this->node]);
	}

	protected function flattenExpr($node, $flattened = [])
	{
		if(!$node) return $flattened;		
		
		if($node->var instanceof \PhpParser\Node\Expr\MethodCall) {
			
			array_unshift($flattened, $node->var);
			return $this->flattenExpr($node->var, $flattened);
		}

		return $flattened;
	}
}