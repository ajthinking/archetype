<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\EndpointProvider;

class ModelProperties extends EndpointProvider
{
    /** attribute => default type */
    protected $propertyMap = [
        'casts'         => 'associativeArray',
        'connection'    => 'string',
        'table'         => 'string',
        'dates'         => 'array',
        'timestamps'    => 'boolean',
        'visible'       => 'array',
        'guarded'       => 'array',
        'unguarded'     => 'array',  
        'fillable'      => 'array',
        'hidden'        => 'array',
    ];

    public function getHandlerMethod($signature, $args)
    {
        return collect($this->propertyMap)->keys()->contains($signature) ? 'fromTemplate' : false;
    }

    public function getEndpoints()
    {
        return collect($this->propertyMap)->keys();
    }

    public function __call($property, $args)
    {
        $defaultType = $this->propertyMap[$property];

        return $this->file->assumeType($defaultType)
            ->protected()
            ->property($property, ...$args);
    }
}