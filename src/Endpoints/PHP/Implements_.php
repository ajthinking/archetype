<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;

class Implements_ extends EndpointProvider
{
    public function implements($name = null)
    {
        if($this->file->directive('add')) return $this->add($name);

        if($name === null) return $this->get();

        return $this->set($name);
    }

    protected function get()
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        return $class ? $class->implements : null;
    }

    protected function set($newImplements)
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        if($class) {
            $class->implements = $newImplements;
        }
        return $this->file->continue();
    }
    
    protected function add($newImplements)
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        if($class) {
            $class->implements = array_merge($class->implements, $newImplements);
        }
        return $this->file->continue();
    }    
}