<?php

namespace PHPFileManipulator\Support\AST;

use PHPFileManipulator\Support\AST\QueryNode;

class Survivor extends QueryNode {

    public $memory;

    public $result;    

    public function __construct($result)
    {
        $this->result = $result;
    }
}