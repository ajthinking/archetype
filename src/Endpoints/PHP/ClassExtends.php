<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\ResourceEndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;

class ClassExtends extends ResourceEndpointProvider
{
    public function get()
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        return $class && isset($class->extends) ? join('\\', $class->extends->parts) : null;
    }

    public function set($newExtends)
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        $class->extends = new \PhpParser\Node\Name($newExtends);
        return $this->file->continue();
    }    
}