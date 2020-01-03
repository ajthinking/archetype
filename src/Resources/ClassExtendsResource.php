<?php

namespace Ajthinking\PHPFileManipulator\Resources;

use Ajthinking\PHPFileManipulator\BaseResource;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;

class ClassExtendsResource extends BaseResource
{
    public function get()
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        return $class ? join('\\', $class->extends->parts) : null;
    }

    public function set($newExtends)
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        $class->extends = new \PhpParser\Node\Name($newExtends);
        return $this->file;
    }    
}