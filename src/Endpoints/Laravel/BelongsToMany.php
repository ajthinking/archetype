<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Support\Snippet;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class BelongsToMany extends EndpointProvider
{
    public function belongsToMany($targets)
    {
        return $this->add($targets);
    }

    protected function set($targets)
    {
        return $this->add($targets);
    }

    protected function add($targets)
    {
        $targets = Arr::wrap($targets);

        $this->file->add()->classMethod(
            collect($targets)->map(function($target) {
                return Snippet::___BELONGS_TO_MANY_METHOD___([
                    '___BELONGS_TO_MANY_METHOD___' => Str::belongsToManyMethodName($target),
                    '___TARGET_CLASS___' => class_basename($target),
                    '___TARGET_IN_DOC_BLOCK___' => Str::belongsToManyDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file->continue();
    }
}