<?php

namespace Ajthinking\PHPFileManipulator\Resources\PHP;

use Ajthinking\PHPFileManipulator\Resources\BaseResource;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;

class ClassName extends BaseResource
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

        return $this->file;
    }     
}