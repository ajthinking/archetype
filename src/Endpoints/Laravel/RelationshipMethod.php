<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\EndpointProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PHPFileManipulator\Support\Snippet;

class RelationshipMethod extends EndpointProvider
{
    public function belongsTo($targets)
    {
        $targets = Arr::wrap($targets);

        $this->file->addClassMethod(
            collect($targets)->map(function($target) {
                return Snippet::___BELONGS_TO_METHOD___([
                    '___BELONGS_TO_METHOD___' => Str::belongsToMethodName($target),
                    '___TARGET_CLASS___' => class_basename($target),
                    '___TARGET_IN_DOC_BLOCK___' => Str::belongsToDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file->continue();        
    }
}