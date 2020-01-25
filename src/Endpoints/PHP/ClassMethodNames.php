<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\ResourceEndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\ClassMethod;

class ClassMethodNames extends ResourceEndpointProvider
{
    public function get()
    {
        return collect(
            (new NodeFinder)->findInstanceOf($this->ast(), ClassMethod::class)
        )->map(function($method) {
            return $method->name->name;
        })->toArray();
    }    
}