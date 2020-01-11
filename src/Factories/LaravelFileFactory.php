<?php

namespace PHPFileManipulator\Factories;

use PHPFileManipulator\LaravelFile;

class LaravelFileFactory
{
    public function __call($method, $args)
    {
        return (new LaravelFile)->$method(...$args);
    }    
}