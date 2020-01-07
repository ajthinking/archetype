<?php

namespace Ajthinking\PHPFileManipulator\Resources;

use Ajthinking\PHPFileManipulator\Support\BaseResource;
use Ajthinking\PHPFileManipulator\Support\LaravelSnippet;

class BelongsToManyMethodsResource extends BaseResource
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return LaravelSnippet::belongsToManyMethod($target);     
            })->toArray()
        );

        return $this->file;
    }
}