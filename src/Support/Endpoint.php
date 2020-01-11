<?php

namespace PHPFileManipulator\Support;

use PHPFileManipulator\PHPFile;
use Illuminate\Support\Str;

abstract class Endpoint
{
    public static function aliases()
    {
        return [Str::camel(
            Str::afterLast(static::class, '\\')
        )];
    }    

    public function __construct(PHPFile $file)
    {
        $this->file = $file;      
    }
}