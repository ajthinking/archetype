<?php

namespace Ajthinking\PHPFileManipulator\Resolvers;

use Illuminate\Support\Str;

class ResourceResolver
{
    public static function canHandle($file, $name)
    {
        return (boolean) static::getHandler($file, $name);
    }

    public static function getHandler($file, $method)
    {
        $resource = $file->resources()->filter(function($resource) use($method) {
            return preg_match("/^$resource\$/i", $method)
                || preg_match("/^add$resource\$/i", $method)
                || preg_match("/^remove$resource\$/i", $method);
        })->first();

        $resourceClassName = "Ajthinking\PHPFileManipulator\Resources\\" . Str::studly($resource) . "Resource";
        return $resource ? new $resourceClassName($file) : false;
    }
}