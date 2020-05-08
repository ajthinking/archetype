<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;

class ClassName extends EndpointProvider
{
    public function className($name = null)
    {
        if($name === null) return $this->get();

        return $this->set($name);
    }

    protected function get()
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        return $class ? $class->name->name : null;
    }    

    protected function set($newClassName)
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        
        if($class) {
            $class->name->name = $newClassName;
        }

        return $this->file->continue();
    }     
}