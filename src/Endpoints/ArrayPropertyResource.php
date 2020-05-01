<?php

namespace PHPFileManipulator\Endpoints;

use PHPFileManipulator\Endpoints\ResourceEndpointProvider;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Expr\ArrayItem;
use PHPFileManipulator\Exceptions\NotImplementedYetException;
use PhpParser\BuilderHelpers;

abstract class ArrayPropertyResource extends ResourceEndpointProvider
{
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
        $result = $this->file->astQuery()
            ->propertyProperty()
            ->where('name->name', $requestedName)
            ->arrayItem()
            ->value
            ->value
            ->get()
            ->toArray();

        return $result ? $result : null;
    }
    
    protected function setItems($requestedName, $items)
    {
        return $this->file->astQuery()
            ->propertyProperty()
            ->where('name->name', $requestedName)
            ->default
            ->replace(BuilderHelpers::normalizeValue($items))
            ->commit()
            ->end()
            ->continue();
    }
}