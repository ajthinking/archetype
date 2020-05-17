<?php

namespace PHPFileManipulator\Endpoints\PHP\Maker;

use PHPFileManipulator\Endpoints\PHP\Maker\PHPTemplate;

class Empty_ extends PHPTemplate
{
    protected $stub = 'empty_.php.stub';

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