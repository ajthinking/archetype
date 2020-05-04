<?php

namespace PHPFileManipulator;

use PHPFileManipulator\PHPFile;

class LaravelFile extends PHPFile 
{
    protected const endpointProviders = [
        // Utillities
        Endpoints\Laravel\Template::class,

        // Resources
        Endpoints\Laravel\ModelProperties::class,
        Endpoints\Laravel\RelationshipMethod::class,
        Endpoints\Laravel\HasOne::class,
        Endpoints\Laravel\HasMany::class,
        //Endpoints\Laravel\BelongsTo::class,
        Endpoints\Laravel\BelongsToMany::class,
    ];

    protected $fileQueryBuilder = Endpoints\Laravel\LaravelFileQueryBuilder::class;

    public function endpointProviders() {
        return parent::endpointProviders()->concat(self::endpointProviders);
    }

    public function templates() {
        return parent::templates()->concat([
            "channel",
            "console",
            "controller",
            "event",
            "exceptionRenderReport",
            "exceptionRender",
            "exceptionReport",
            "exception",
            "job",
            "listenerDuck",
            "listenerQueuedDuck",
            "listenerQueued",
            "listener",
            "mail",
            "markdownMail",
            "markdownNotification",
            "model",
            "notification",
            "observer_plain",
            "observer",
            "policy_plain",
            "policy",
            "provider",
            "request",
            "resourceCollection",
            "resource",
            "rule",
            "test",
            "test_unit",
        ]);
    }
    
    public function tags()
    {
        return [
            // File directories
            'model_default_directory' => 'app',
            'controller_default__directory' => 'app/Http/Controllers',
        ];
    }
}