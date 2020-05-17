<?php

namespace PHPFileManipulator\Endpoints\Laravel\Maker;

use PHPFileManipulator\Endpoints\Laravel\Maker\LaravelTemplate;

class Model extends LaravelTemplate
{
    protected $stub = 'model.stub';

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