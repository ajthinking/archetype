<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Support\Snippet;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class HasOne extends EndpointProvider
{
    public function hasOne($targets)
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
                return Snippet::___HAS_ONE_METHOD___([
                    '___HAS_ONE_METHOD___' => Str::hasOneMethodName($target),
                    '___TARGET_CLASS___' => class_basename($target),
                    '___TARGET_IN_DOC_BLOCK___' => Str::hasOneDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file->continue();
    }
}