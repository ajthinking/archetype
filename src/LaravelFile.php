<?php

namespace Archetype;

use Archetype\PHPFile;

class LaravelFile extends PHPFile
{
    protected const endpointProviders = [
        // Utilities
        Endpoints\Laravel\LaravelMake::class,

        // Resources
        Endpoints\Laravel\BelongsTo::class,
        Endpoints\Laravel\BelongsToMany::class,
        Endpoints\Laravel\HasMany::class,
        Endpoints\Laravel\HasOne::class,
        Endpoints\Laravel\ModelProperties::class,
    ];

    protected string $fileQueryBuilder = Endpoints\Laravel\LaravelFileQueryBuilder::class;

    public function endpointProviders()
    {
        return parent::endpointProviders()->concat(self::endpointProviders);
    }
}
