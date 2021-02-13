<?php

namespace Archetype\Facades;

use Illuminate\Support\Facades\Facade;

class Project extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Archetype\Facades\Project';
    }
}
