<?php

namespace PHPFileManipulator\Endpoints\PHP\Template;

use PHPFileManipulator\Endpoints\BaseTemplate;

class Empty_ extends BaseTemplate
{
    const stubPath = __DIR__ . '/stubs/empty_.php.stub';

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