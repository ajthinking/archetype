<?php

namespace PHPFileManipulator\Support\AST;

use PHPFileManipulator\Support\AST\QueryNode;

class Survivor extends QueryNode {

    public $memory;

    public $results;    

    public function __construct($results)
    {
        $this->results = $results;
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
        $this->results = $result;
        return $this;
    }


}