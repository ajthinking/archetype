<?php

namespace PHPFileManipulator\Endpoints\Laravel\Maker;

use PHPFileManipulator\Endpoints\Laravel\Maker\LaravelTemplate;

class Command extends LaravelTemplate
{
    const defaults = [
        'command' => 'command:name'
    ];

    protected $stub = 'console.stub';

    public function __construct($name, $options = self::defaults)
    {
        $this->filename = $name;
        $this->options = $options;
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
        collect($this->replacementPairs())->each(function($value, $key) use(&$contents) {
            
            $contents = str_replace($key, $value, $contents);

        });
        
        return $contents;
    }

    protected function replacementPairs()
    {
        return collect($this->options)->concat([
            '___CLASS___' => $this->filename,
            //'dummy:command' => $this->options['command'] ?? 'dummy:command',
        ])->toArray();
    }
}