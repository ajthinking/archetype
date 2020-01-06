<?php

namespace Ajthinking\PHPFileManipulator\Resources;

use Ajthinking\PHPFileManipulator\BaseResource;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Expr\Array_;

class FillableResource extends BaseResource
{
    public function get()
    {
        $propertyGroups = (new NodeFinder)->findInstanceOf($this->ast(), Property::class);
        if(!$propertyGroups) return null;

        $fillableAST = collect($propertyGroups)->map(function($propertyGroup) {
            // Assume only one property per statement
            return $propertyGroup->props[0];
        })->filter(function($property) {
            return $property->name->name == 'fillable';
        })->first();

        if(!$fillableAST) return null;

        if(!$fillableAST->default instanceof Array_) return null;

        return collect($fillableAST->default->items)->map(function($item) {
            return $item->value->value;
        })->toArray();
    }

    // public function set($newExtends)
    // {
    //     $class = (new NodeFinder)->findFirstInstanceOf($this->ast(), Class_::class);
    //     $class->extends = new \PhpParser\Node\Name($newExtends);
    //     return $this->file;
    // }    
}