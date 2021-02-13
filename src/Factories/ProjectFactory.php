<?php

namespace Archetype\Factories;

use Archetype\Project;

class ProjectFactory
{
    const PROJECT_TYPE = Project::class;

    public function __call($method, $args)
    {
        return self::__makeFileInstance()->$method(...$args);
    }

    public static function __callStatic($method, $args)
    {
        return self::__makeFileInstance()->$method(...$args);
    }

    protected static function __makeFileInstance()
    {
        $class = static::PROJECT_TYPE;
        return new $class;
    }
}
