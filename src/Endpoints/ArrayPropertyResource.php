<?php

namespace PHPFileManipulator\Endpoints;

use PHPFileManipulator\Endpoints\ResourceEndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Expr\ArrayItem;
use PHPFileManipulator\Exceptions\NotImplementedYetException;

abstract class ArrayPropertyResource extends ResourceEndpointProvider
{
    protected function canUseReflection()
    {
        return $this->file->getReflection() && !$this->file->hasModifications();
    }

    protected function getWithReflection($name)
    {
        return $this->file->getReflection()->getDefaultProperties()[$name];
    }

    protected function getWithParser($name)
    {
        return $this->items($name);
    }

    protected function items($requestedName)
    {
        $propertyGroups = (new NodeFinder)->findInstanceOf($this->ast(), Property::class);
        if(!$propertyGroups) return null;

        $ast = collect($propertyGroups)->map(function($propertyGroup) {
            // Assume only one property per statement
            return $propertyGroup->props[0];
        })->filter(function($property) use($requestedName) {
            return $property->name->name == $requestedName;
        })->first();

        if(!$ast) return null;

        if(!$ast->default instanceof Array_) return null;

        return collect($ast->default->items)->map(function($item) {
            return $item->value->value;
        })->toArray();
    }
    
    protected function setItems($requestedName, $items)
    {
        $propertyGroups = (new NodeFinder)->findInstanceOf($this->ast(), Property::class);
        if(!$propertyGroups) return null;

        $ast = collect($propertyGroups)->map(function($propertyGroup) {
            // Assume only one property per statement
            return $propertyGroup->props[0];
        })->filter(function($property) use($requestedName) {
            return $property->name->name == $requestedName;
        })->first();

        if(!$ast) {
            // Fix later
            throw new NotImplementedYetException('Can not insert fillables if not allready present');
        };

        $ast->default = new Array_(
            collect($items)->map(function($item) {
                return new ArrayItem(
                    new String_($item)
                );
            })->toArray()
        );

        return $this->file;
    }
}