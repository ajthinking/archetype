<?php

namespace PHPFileManipulator\Resolvers;

use PHPFileManipulator\PHPFile;
use PHPFileManipulator\Resources\Fillable;
use Illuminate\Support\Str;

class ResourceResolver
{
    public static function canHandle($file, $name)
    {
        return (boolean) static::getHandler($file, $name);
    }

    public static function getHandler($file, $method)
    {
        $apiMap = static::getAPIMap($file->endpoints());

        $accessor = $apiMap->keys()->filter(function($accessor) use($method) {
            return preg_match("/^$accessor\$/i", $method)
                || preg_match("/^add$accessor\$/i", $method)
                || preg_match("/^remove$accessor\$/i", $method);
        })->first();

        return $accessor ? new $apiMap[$accessor]($file) : false;
    }

    public static function getAPIMap($endpoints)
    {
        return $endpoints->map(function($endpoint) {
            return collect($endpoint::aliases())->flatMap(function($alias) use($endpoint) {
                return [$alias => $endpoint];
            });
        })->collapse();
    }
}