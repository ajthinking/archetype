<?php

namespace Archetype\Support\AST;

use Archetype\Support\AST\QueryNode;

class Survivor extends QueryNode
{
    public $memory;

    public $result;

	public $parent;

    final public function __construct($result)
    {
        $this->result = $result;
    }

    public static function fromParent($parent)
    {
        $survivor = new static([]);
        $survivor->parent = $parent;
        $survivor->memory = $parent->memory ? $parent->memory : [];
        return $survivor;
    }

    public function withResult($result)
    {
        $this->result = $result;
        return $this;
    }
}
