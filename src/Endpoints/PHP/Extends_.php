<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;

class Extends_ extends EndpointProvider
{
    public function extends($name = null)
    {
        if($name === null) return $this->get();

        return $this->set($name);
    }

    protected function get()
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        return $class && isset($class->extends) ? join('\\', $class->extends->parts) : null;
    }

    protected function set($newExtends)
    {
        $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
        $class->extends = new \PhpParser\Node\Name($newExtends);
        return $this->file->continue();
    }    
}