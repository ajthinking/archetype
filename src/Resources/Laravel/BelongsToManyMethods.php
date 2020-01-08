<?php

namespace Ajthinking\PHPFileManipulator\Resources\Laravel;

use Ajthinking\PHPFileManipulator\Resources\BaseResource;
use LaravelFile;
use Illuminate\Support\Str;

class BelongsToManyMethods extends BaseResource
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return LaravelFile::snippet('___BELONGS_TO_MANY_METHOD___', [
                    '___BELONGS_TO_MANY_METHOD___' => Str::belongsToManyMethodName($target),
                    '___TARGET_IN_DOC_BLOCK___' => Str::belongsToManyDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file;
    }
}