<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\ResourceEndpointProvider;
use PHPFileManipulator\Support\Snippet;
use Illuminate\Support\Str;

class BelongsToMethods extends ResourceEndpointProvider
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return Snippet::___BELONGS_TO_METHOD___([
                    '___BELONGS_TO_METHOD___' => Str::belongsToMethodName($target),
                    '___TARGET_CLASS___' => class_basename($target),
                    '___TARGET_IN_DOC_BLOCK___' => Str::belongsToDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file;
    }
}