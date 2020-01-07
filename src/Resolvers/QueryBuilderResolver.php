<?php

namespace Ajthinking\PHPFileManipulator\Resolvers;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use Ajthinking\PHPFileManipulator\Support\QueryBuilder;

class QueryBuilderResolver
{
    public static function canHandle($_, $name)
    {
        return (boolean) static::getHandler($_, $name);
    }

    public static function getHandler($_, $method)
    {
        $reflection = new ReflectionClass(QueryBuilder::class);
        $methods = collect($reflection->getMethods(ReflectionMethod::IS_PUBLIC))->pluck('name');
        return collect($methods)->contains($method) ? new QueryBuilder : false;
    }
}