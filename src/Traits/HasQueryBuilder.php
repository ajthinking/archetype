<?php

namespace Ajthinking\PHPFileManipulator\Traits;

use Ajthinking\PHPFileManipulator\QueryBuilder;

trait HasQueryBuilder
{
    public static function all()
    {
        return (new QueryBuilder)->all();
    }

    public static function in($args)
    {
        return (new QueryBuilder())->in($args);
    }

    public static function where($args)
    {
        // resource query
        // filename query
        // function query
    }    
}