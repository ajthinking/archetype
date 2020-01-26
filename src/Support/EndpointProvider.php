<?php

namespace PHPFileManipulator\Support;

use PHPFileManipulator\PHPFile;
use Illuminate\Support\Str;

abstract class EndpointProvider
{
    public static function aliases()
    {
        return [
            Str::camel(class_basename(static::class))
        ];
    }    

    public function canHandle($signature, $args)
    {
        return (boolean) $this->getHandlerMethod($signature, $args);
    }

    public function getHandlerMethod($signature, $args)
    {
        return false;
    }

    public function getEndpoints()
    {
        return [];
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