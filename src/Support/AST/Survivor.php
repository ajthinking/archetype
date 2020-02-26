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
}