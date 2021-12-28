<?php

namespace Archetype\Tests\Support\Facades;

use Illuminate\Support\Facades\Facade;

class TestablePHPFile extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'TestablePHPFile';
    }
}
