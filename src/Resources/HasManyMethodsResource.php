<?php

namespace Ajthinking\PHPFileManipulator\Resources;

use Ajthinking\PHPFileManipulator\BaseResource;
use Ajthinking\PHPFileManipulator\LaravelSnippet;

class HasManyMethodsResource extends BaseResource
{
    public function add($targets)
    {
        $this->file->addClassMethods(
            collect($targets)->map(function($target) {
                return LaravelSnippet::hasManyMethod($target);     
            })->toArray()
        );

        return $this->file;
    }
}