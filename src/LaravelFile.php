<?php

namespace PHPFileManipulator;

use PHPFileManipulator\PHPFile;

class LaravelFile extends PHPFile 
{
    protected $file_query_builder = Endpoints\Laravel\LaravelFileQueryBuilder::class;

    public function endpointProviders() {
        return parent::endpointProviders()->concat($this->endpoint_providers);
    }

    protected $endpoint_providers = [
        // Utillities
        Endpoints\Laravel\Template::class,

        // Resources
        Endpoints\Laravel\Fillable::class,
        Endpoints\Laravel\Hidden::class,
        Endpoints\Laravel\HasOneMethods::class,
        Endpoints\Laravel\HasManyMethods::class,
        Endpoints\Laravel\BelongsToMethods::class,
        Endpoints\Laravel\BelongsToManyMethods::class,
    ];

    public function templates() {
        return parent::templates()->concat([
            "channel",
            "console",
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
}