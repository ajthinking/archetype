<?php

namespace Archetype\Endpoints\Laravel\Maker;

use Archetype\Endpoints\Laravel\Maker\LaravelTemplate;

class Unimplemented extends LaravelTemplate
{
    const stubPath = __DIR__ . '/../../PHP/Maker/stubs/empty_.php.stub';

    public function __construct($name)
    {
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