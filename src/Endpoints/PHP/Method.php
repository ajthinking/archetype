<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Class_;
use Illuminate\Support\Arr;

class Method extends EndpointProvider
{
    public function classMethod($methods = null)
    {
        // set? | no distinction between add/set
        if($this->file->directive('add') || $methods) return $this->set($methods);

        // get?
        return $this->get();
    }

    protected function get()
    {
        return (new NodeFinder)->findInstanceOf($this->ast(), ClassMethod::class);
    }

    protected function set($methods)
    {
        // no value but has value from intermidiate add directive?
        if(!$methods && $this->file->directive('addValue')) {
            $methods = $this->file->directive('addValue');
        }

        $methods = Arr::wrap($methods);
        
        collect($methods)->each(function($method) {
            $this->setOne($method);
        });

        return $this->file->continue();
    }

    protected function setOne($method)
    {
        // replace existing
        $replaced = $this->file->astQuery()
            ->classMethod()
            ->where('name->name', $method->name->name)
            ->replace($method)
            ->commit()
            ->first();

        if($replaced) return;

        // insert new
        $this->file->astQuery()
            ->class()
            ->insertStmt($method)
            ->commit();
    }

    protected function getClass()
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        
        if(!$class) throw Exception('Could not resolve a class.');

        return $class;
    }
}