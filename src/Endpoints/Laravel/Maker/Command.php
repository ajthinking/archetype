<?php

namespace PHPFileManipulator\Endpoints\Laravel\Maker;

use PHPFileManipulator\Endpoints\Laravel\Maker\LaravelTemplate;

class Command extends LaravelTemplate
{
    protected $stub = 'console.stub';

    public function __construct($name, $options = [])
    {
        $this->filename = $name;
        $this->namespace = 'Some\App\\Namespaze';
        $this->class = $name;
        $this->command = $options['command'] ?? 'command:name';
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
        $contents = str_replace(['DummyNamespace'], $this->namespace, $contents);
        $contents = str_replace(['{{ class }}', 'DummyClass', '___class___'], $this->class, $contents);
        $contents = str_replace(['{{ command }}', 'dummy:command', '___command___'], $this->command, $contents);

        return $contents;
    }
}