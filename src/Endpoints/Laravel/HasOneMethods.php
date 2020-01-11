<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\BaseResource;
use PHPFileManipulator\Support\Snippet;
use Illuminate\Support\Str;

class HasOneMethods extends BaseResource
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return Snippet::___HAS_ONE_METHOD___([
                    '___HAS_ONE_METHOD___' => Str::hasOneMethodName($target),
                    '___TARGET_CLASS___' => class_basename($target),
                    '___TARGET_IN_DOC_BLOCK___' => Str::hasOneDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file;
    }
}