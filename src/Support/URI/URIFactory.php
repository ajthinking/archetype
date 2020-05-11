<?php

namespace PHPFileManipulator\Support\URI;

use Illuminate\Support\Str;
use PHPFileManipulator\Support\URI\PathURI;
use PHPFileManipulator\Support\URI\NameURI;

class URIFactory
{
    public static function make($input)
    {
        // Empty? -> path
        if($input === '') return new PathURI($input);

        // Extension? -> path
        if(Str::endsWith($input, '.php')) return new PathURI($input);

        // Forward slash? -> path
        if(Str::contains($input, DIRECTORY_SEPARATOR)) return new PathURI($input);

        // Backward slash? -> name
        if(Str::contains($input, '\\')) return new NameURI($input);

        // Starts with lowercase? -> path
        if($input[0] === strtolower($input[0])) return new PathURI($input);

        // Default
        return new NameURI($input);
    }
}