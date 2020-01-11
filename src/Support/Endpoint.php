<?php

namespace PHPFileManipulator\Support;

use PHPFileManipulator\PHPFile;
use Illuminate\Support\Str;

abstract class Endpoint
{
    public static function aliases()
    {
        return [
            Str::camel(class_basename(static::class))
        ];
    }    

    public function __construct(PHPFile $file)
    {
        $this->file = $file;      
    }

    public function ast()
    {
        return $this->file->ast();
    }
}