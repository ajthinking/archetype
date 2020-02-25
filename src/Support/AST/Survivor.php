<?php

namespace PHPFileManipulator\Support\AST;

class Survivor {

    public $memory;

    public $result;    

    public function __construct($result)
    {
        $this->result = $result;
    }
}