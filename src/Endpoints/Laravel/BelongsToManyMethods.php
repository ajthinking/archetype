<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\BaseResource;
use PHPFileManipulator\Support\Snippet;
use Illuminate\Support\Str;

class BelongsToManyMethods extends BaseResource
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return Snippet::___BELONGS_TO_MANY_METHOD___([
                    '___BELONGS_TO_MANY_METHOD___' => Str::belongsToManyMethodName($target),
                    '___TARGET_CLASS___' => class_basename($target),
                    '___TARGET_IN_DOC_BLOCK___' => Str::belongsToManyDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file;
    }
}