<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\BaseResource;
use PHPFileManipulator\Support\Snippet;
use Illuminate\Support\Str;

class HasManyMethods extends BaseResource
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return Snippet::___HAS_MANY_METHOD___([
                    '___HAS_MANY_METHOD___' => Str::hasManyMethodName($target),
                    '___TARGET_CLASS___' => collect(explode('\\', $target))->last(),
                    '___TARGET_IN_DOC_BLOCK___' => Str::hasManyDocBlockName($target)
                ]);     
            })->toArray()
        );

        return $this->file;
    }
}