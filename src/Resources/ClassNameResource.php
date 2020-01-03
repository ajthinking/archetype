<?php

namespace Ajthinking\PHPFileManipulator\Resources;

use Ajthinking\PHPFileManipulator\BaseResource;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;

class ClassNameResource extends BaseResource
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