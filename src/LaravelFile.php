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