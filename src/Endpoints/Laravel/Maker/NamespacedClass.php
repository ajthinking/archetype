<?php

namespace Archetype\Endpoints\Laravel\Maker;

use Archetype\Endpoints\Laravel\Maker\LaravelTemplate;
use Archetype\Support\URI;

class NamepspacedClass extends LaravelTemplate
{
    public function __construct($name)
    {
        $uri = URI::make($name);

        $this->filename = $name;
    }
    
    protected function filename()
    {
        return $this->filename;
    }

    protected function relativeDir()
    {
        return '';
    }
}