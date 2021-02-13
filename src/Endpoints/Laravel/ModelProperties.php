<?php

namespace Archetype\Endpoints\Laravel;

use Archetype\Endpoints\EndpointProvider;

class ModelProperties extends EndpointProvider
{
    /** attribute => default type */
    protected $propertyMap = [
        'casts'         => 'array',
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

    protected function getHandlerMethod($signature, $args)
    {
        return collect($this->propertyMap)->keys()->contains($signature) ? $signature : false;
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
