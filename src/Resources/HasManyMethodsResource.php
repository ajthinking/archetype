<?php

namespace Ajthinking\PHPFileManipulator\Resources;

use Ajthinking\PHPFileManipulator\Support\BaseResource;
use LaravelFile;
use Illuminate\Support\Str;

class HasManyMethodsResource extends BaseResource
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return LaravelFile::snippet('___HAS_MANY_METHOD___', [
                    '___HAS_MANY_METHOD___' => Str::hasManyMethodName($target),
                    '___TARGET_CLASS___' => $target,
                    '___TARGET_IN_DOC_BLOCK___' => Str::hasManyDocBlockName($target)
                ]);     
            })->toArray()
        );

        return $this->file;
    }
}