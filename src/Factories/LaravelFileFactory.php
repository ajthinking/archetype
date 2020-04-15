<?php

namespace PHPFileManipulator\Factories;

use PHPFileManipulator\LaravelFile;

class LaravelFileFactory
{
    public function __call($method, $args)
    {
        return (new LaravelFile)->$method(...$args);
    }

    public static function __callStatic($method, $args)
    {
        return (new LaravelFile)->$method(...$args);
    }
}