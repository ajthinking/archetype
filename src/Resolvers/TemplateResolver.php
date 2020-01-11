<?php

namespace PHPFileManipulator\Resolvers;

use Illuminate\Support\Str;
use PHPFileManipulator\Template;

class TemplateResolver
{
    public static function canHandle($file, $name)
    {
        return (boolean) static::getHandler($file, $name);
    }

    public static function getHandler($file, $name)
    {
        return $file->templates()->contains($name) ? $file : false;
    }
}