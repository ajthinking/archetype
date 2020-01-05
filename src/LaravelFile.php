<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\PHPFile;

class LaravelFile extends PHPFile 
{
    public function resources() {
        return parent::resources()->concat([
            'casts',
            'fillable',
            'hidden',
            'routes',
            'hasOneMethods',
            'hasManyMethods',
            'belongsToMethods',
            'belongsToManyMethods',
        ]);
    }

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
            "markdown",
            "model",
            "notification",
            "observerPlain",
            "observer",
            "pivotModel",
            "policyPlain",
            "policy",
            "provider",
            "request",
            "resourceCollection",
            "resource",
            "routes",
            "rule",
            "test",
            "unitTest",
        ]);
    }    
}