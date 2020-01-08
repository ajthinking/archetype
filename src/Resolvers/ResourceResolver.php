<?php

namespace Ajthinking\PHPFileManipulator\Resolvers;

use Ajthinking\PHPFileManipulator\PHPFile;
use Ajthinking\PHPFileManipulator\Resources\Fillable;
use Illuminate\Support\Str;

class ResourceResolver
{
    public static function canHandle($file, $name)
    {
        return (boolean) static::getHandler($file, $name);
    }

    public static function getHandler($file, $method)
    {
        $resourceMap = static::getResourceMap($file->resources());

        $accessor = $resourceMap->keys()->filter(function($accessor) use($method) {
            return preg_match("/^$accessor\$/i", $method)
                || preg_match("/^add$accessor\$/i", $method)
                || preg_match("/^remove$accessor\$/i", $method);
        })->first();

        return $accessor ? new $resourceMap[$accessor]($file) : false;
    }

    public static function getResourceMap($resources)
    {
        return $resources->map(function($resource) {
            return collect($resource::aliases())->flatMap(function($alias) use($resource) {
                return [$alias => $resource];
            });
        })->collapse();
    }
}