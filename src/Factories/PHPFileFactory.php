<?php

namespace Archetype\Factories;

use Archetype\PHPFile;

class PHPFileFactory
{
    const FILE_TYPE = PHPFile::class;

    public function __call($method, $args)
    {
        return static::__makeFileInstance()->$method(...$args);
    }

    public static function __callStatic($method, $args)
    {
        return static::__makeFileInstance()->$method(...$args);
    }

    protected static function __makeFileInstance()
    {
        $class = static::FILE_TYPE;
        $instance = new $class;
        $instance->inputDriver(static::__driver('input'));

        $instance->outputDriver(static::__driver('output'));

        return $instance;
    }

    protected static function __driver($name)
    {
        $driver = [
            "input" => config('archetype.input', \Archetype\Drivers\FileInput::class),
            "output" => config('archetype.output', \Archetype\Drivers\FileOutput::class),
        ][$name];

        return new $driver;
    }
}
