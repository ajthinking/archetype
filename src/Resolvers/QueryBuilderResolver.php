<?php

namespace Ajthinking\PHPFileManipulator\Resolvers;

use Illuminate\Support\Str;
use Ajthinking\PHPFileManipulator\Template;
use ReflectionClass;

class QueryBuilderResolver
{
    public static function canHandle($file, $method)
    {
        $reflection = new ReflectionClass('Ajthinking\PHPFileManipulator\Traits\HasQueryBuilder');
        $traitMethods = $reflection->getMethods();        
        return collect($traitMethods)->contains($method) ? $file : false;
    }
}