<?php

namespace Ajthinking\PHPFileManipulator\Resources;

use Ajthinking\PHPFileManipulator\Support\BaseResource;
use LaravelFile;
use Illuminate\Support\Str;

class HasOneMethodsResource extends BaseResource
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return LaravelFile::snippet('___HAS_ONE_METHOD___', [
                    '___HAS_ONE_METHOD___' => Str::hasOneMethodName($target),
                    '___TARGET_IN_DOC_BLOCK___' => Str::hasOneDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file;
    }
}