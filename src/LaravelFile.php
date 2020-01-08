<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\PHPFile;

class LaravelFile extends PHPFile 
{
    public function resources() {
        return parent::resources()->concat($this->resources);
    }

    protected $resources = [
        Resources\Laravel\Fillable::class,
        Resources\Laravel\Hidden::class,
        Resources\Laravel\HasOneMethods::class,
        Resources\Laravel\HasManyMethods::class,
        Resources\Laravel\BelongsToMethods::class,
        Resources\Laravel\BelongsToManyMethods::class,
    ];

    public function templates() {
        return parent::templates()->concat([
            "channel",
            "console",
            "eventHandlerQueued",
            "eventHandler",
            "event",
            "exceptionRenderReport",
            "exceptionRender",
            "exceptionReport",
            "exception",
            "jobQueued",
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
            "pivot_model",
            "policy_plain",
            "policy",
            "provider",
            "request",
            "resourceCollection",
            "resource",
            "rule",
            "test",
            "unitTest",
        ]);
    }    
}