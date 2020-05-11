<?php

namespace PHPFileManipulator;

use PHPFileManipulator\PHPFile;

class LaravelFile extends PHPFile 
{
    protected const endpointProviders = [
        // Utillities
        Endpoints\Laravel\LaravelMaker::class,

        // Resources
        Endpoints\Laravel\ModelProperties::class,
        Endpoints\Laravel\HasOne::class,
        Endpoints\Laravel\HasMany::class,
        Endpoints\Laravel\BelongsTo::class,
        Endpoints\Laravel\BelongsToMany::class,
    ];

    protected $fileQueryBuilder = Endpoints\Laravel\LaravelFileQueryBuilder::class;

    public function endpointProviders() {
        return parent::endpointProviders()->concat(self::endpointProviders);
    }
}