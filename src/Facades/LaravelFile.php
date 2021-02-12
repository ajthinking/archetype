<?php

namespace Archetype\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelFile extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LaravelFile';
    }
}
