<?php

namespace Archetype\Endpoints\Laravel\Maker;

use Archetype\Support\URI;
use Archetype\Endpoints\Laravel\Maker\LaravelTemplate;

class Model extends LaravelTemplate
{
    protected $stub = 'model.stub';

    public function __construct($name, $options = [])
    {
        $this->uri = URI::make($name); // TODO

        $this->filename = $name;
        $this->namespace = 'Some\App\\Namespaze';
        $this->class = $name;
    }
    
    protected function filename()
    {
        return $this->filename;
    }

    protected function relativeDir()
    {
        return '';
    }

    protected function populate($contents)
    {
        $contents = str_replace(['DummyNamespace', '___namespace___'], $this->namespace, $contents);
        $contents = str_replace(['{{ class }}', 'DummyClass', '___class___'], $this->class, $contents);

        return $contents;
    }
}