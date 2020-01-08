<?php

namespace Ajthinking\PHPFileManipulator\Resources;

use Ajthinking\PHPFileManipulator\Support\BaseResource;
use LaravelFile;
use Illuminate\Support\Str;

class BelongsToMethodsResource extends BaseResource
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return LaravelFile::snippet('___BELONGS_TO_METHOD___', [
                    '___BELONGS_TO_METHOD___' => Str::belongsToMethodName($target),
                    '___TARGET_IN_DOC_BLOCK___' => Str::belongsToDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file;
    }
}