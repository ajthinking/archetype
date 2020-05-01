<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\ResourceEndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;

class ClassName extends ResourceEndpointProvider
{
    public function get()
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        return $class ? $class->name->name : null;        
    }

    public function set($newClassName)
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        
        if($class) {
            $class->name->name = $newClassName;
        }

        return $this->file->continue();
    }     
}