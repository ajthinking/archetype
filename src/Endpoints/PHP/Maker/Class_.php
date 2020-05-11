<?php

namespace PHPFileManipulator\Endpoints\PHP\Maker;

use PHPFileManipulator\Endpoints\PHP\Maker\PHPTemplate;

class Class_ extends PHPTemplate
{
    const stubPath = __DIR__ . '/stubs/class_.php.stub';

    public function __construct($name)
    {
        $this->filename = $name;
        $this->className = $name;
    }
    
    protected function filename()
    {
        return $this->filename;
    }

    protected function relativeDir()
    {
        return 'app';
    }

    protected function contents($template)
    {
        return str_replace('___CLASS___', $this->className, $template);
    }
}