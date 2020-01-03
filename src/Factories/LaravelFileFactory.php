<?php

namespace Ajthinking\PHPFileManipulator\Factories;

use Ajthinking\PHPFileManipulator\LaravelFile;

class LaravelFileFactory
{
    public function __call($method, $args)
    {
        return (new LaravelFile)->$method(...$args);
    }    
}