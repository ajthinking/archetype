<?php

namespace Ajthinking\PHPFileManipulator\Resources\Laravel;

use Ajthinking\PHPFileManipulator\Resources\BaseResource;
use LaravelFile;
use Illuminate\Support\Str;

class BelongsToMethods extends BaseResource
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return LaravelFile::snippet('___BELONGS_TO_METHOD___', [
                    '___BELONGS_TO_METHOD___' => Str::belongsToMethodName($target),
                    '___TARGET_CLASS___' => collect(explode('\\', $target))->last(),
                    '___TARGET_IN_DOC_BLOCK___' => Str::belongsToDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file;
    }
}