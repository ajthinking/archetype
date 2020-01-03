<?php

namespace Ajthinking\PHPFileManipulator\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelFile extends Facade {
   protected static function getFacadeAccessor() { return 'LaravelFile'; }

    /**
     * Resolve a new instance for the facade
     *
     * @return mixed
     */
    public static function refresh()
    {
        static::clearResolvedInstance(static::getFacadeAccessor());
        return static::getFacadeRoot();
    }
}