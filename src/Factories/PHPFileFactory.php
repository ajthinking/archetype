<?php

namespace PHPFileManipulator\Factories;

use PHPFileManipulator\PHPFile;

class PHPFileFactory
{
    const FILE_TYPE = PHPFile::class;

    public function __call($method, $args)
    {
        return self::__delegate($method, $args);
    }

    public static function __callStatic($method, $args)
    {
        return self::__delegate($method, $args);
    }

    protected static function __delegate($method, $args)
    {
        $class = static::FILE_TYPE;

        return (new $class(
            self::__driver('input'),
            self::__driver('output'),
        ))->$method(...$args);
    }

    protected static function __driver($name)
    {
        return [
            "input" => config('php-file-manipulator.input', \PHPFileManipulator\Drivers\FileInput::class),
            "output" => config('php-file-manipulator.input', \PHPFileManipulator\Drivers\FileInput::class),
        ][$name];
    }    
}