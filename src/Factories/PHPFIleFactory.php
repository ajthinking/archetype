<?php

namespace Ajthinking\PHPFileManipulator\Factories;

use Ajthinking\PHPFileManipulator\PHPFile;

class PHPFileFactory
{
    public function __call($method, $args)
    {
        return (new PHPFile)->$method(...$args);
    }    
}