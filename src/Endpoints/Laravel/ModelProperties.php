<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\EndpointProvider;

class ModelProperties extends EndpointProvider
{
    protected $methods = [
        "casts",
        "connection",
        "table",
        "dates",
        "timestamps",
        "visible",
        "guarded",
        "unguarded",  
        //"fillable",
        //"hidden",
    ];

    public function getHandlerMethod($signature, $args)
    {
        return collect($this->methods)->contains($signature) ? 'fromTemplate' : false;
    }

    public function getEndpoints()
    {
        return $this->methods;
    }

    public function __call($property, $args)
    {
        return $args == [] ? $this->file->property($property) : $this->file->property($property, ...$args);
    }
}