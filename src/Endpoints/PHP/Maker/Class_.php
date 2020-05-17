<?php

namespace PHPFileManipulator\Endpoints\PHP\Maker;

use PHPFileManipulator\Endpoints\PHP\Maker\PHPTemplate;

class Class_ extends PHPTemplate
{
    protected $stub = 'class_.php.stub';

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

    protected function populate($contents)
    {
        return str_replace('___CLASS___', $this->className, $contents);
    }
}