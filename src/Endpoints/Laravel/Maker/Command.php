<?php

namespace PHPFileManipulator\Endpoints\Laravel\Maker;

use PHPFileManipulator\Endpoints\Laravel\Maker\LaravelTemplate;

class Command extends LaravelTemplate
{
    const templateKeys = [
        '{{ command }}' => 'command',
        'dummy:command' => 'command',
        '___command___' => 'command',
    ];    

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



    protected function replacementPairs()
    {
        $pairs = collect($this->options)->filter(function($value, $key) {
            return !collect($this->templateKeys)->contains($key);
        });

        $renamings = collect($this->templateKeys)->keys();

        collect($this->options)->flatMap(function($value, $key) {

        });










        return $this->concat([
            '___CLASS___' => $this->filename,
            //'dummy:command' => $this->options['command'] ?? 'dummy:command',
        ])->toArray();
    }
}