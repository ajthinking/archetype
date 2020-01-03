<?php

namespace Ajthinking\PHPFileManipulator\Resources;

use Ajthinking\PHPFileManipulator\BaseResource;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Class_;

class ClassMethodsResource extends BaseResource
{
    public function get()
    {
        return (new NodeFinder)->findInstanceOf($this->ast(), ClassMethod::class);
    }
    
    public function add($methods)
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        
        if(!$class) { /** throw some exception */ }
        
        $class->stmts = collect($class->stmts)->concat($methods)->toArray();

        return $this->file;
    }
}