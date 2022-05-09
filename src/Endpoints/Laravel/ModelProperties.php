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

    public function __call($property, $args)
    {
        $defaultType = $this->propertyMap[$property];

        return $this->file->assumeType($defaultType)
            ->protected()
            ->property($property, ...$args);
    }
}
