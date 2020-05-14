<?php

namespace PHPFileManipulator\Endpoints\Laravel\Maker;

use PHPFileManipulator\Endpoints\Laravel\Maker\LaravelTemplate;
use PHPFileManipulator\Support\URI\URIFactory;

class NamepspacedClass extends LaravelTemplate
{
    public function __construct($name)
    {
        $uri = UriFactory::make($name);

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