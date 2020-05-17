<?php

namespace PHPFileManipulator\Endpoints\Laravel\Maker;

use PHPFileManipulator\Endpoints\Laravel\Maker\LaravelTemplate;

class Command extends LaravelTemplate
{
    protected $stub = 'console.stub';

    public function __construct($name, $signature = 'command:name')
    {
        $this->filename = $name;
        $this->signature = $signature;
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