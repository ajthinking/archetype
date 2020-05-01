<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\ResourceEndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;

class ClassImplements extends ResourceEndpointProvider
{
    public function get()
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        return $class ? $class->implements : null;
    }

    public function set($newImplements)
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        if($class) {
            $class->implements = $newImplements;
        }
        return $this->file->continue();
    }
    
    public function add($newImplements)
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        if($class) {
            $class->implements = array_merge($class->implements, $newImplements);
        }
        return $this->file->continue();
    }    
}