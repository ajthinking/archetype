<?php

namespace Archetype\Commands\Demo;

use Archetype\LaravelFile;

class CustomizedLaravelFile extends LaravelFile
{
    public function itHasExtras()
    {
        return "Something extra!";
    }
}
