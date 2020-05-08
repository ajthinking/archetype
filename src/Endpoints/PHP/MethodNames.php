<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\ClassMethod;

class MethodNames extends EndpointProvider
{
    public function methodNames()
    {
        return $this->get();
    }   

    protected function get()
    {
        return collect(
            (new NodeFinder)->findInstanceOf($this->ast(), ClassMethod::class)
        )->map(function($method) {
            return $method->name->name;
        })->toArray();
    }    
}