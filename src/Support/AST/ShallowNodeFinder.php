<?php

namespace Archetype\Support\AST;

use PhpParser\NodeFinder;

class ShallowNodeFinder extends NodeFinder
{
    public function findInstanceOf($node, string $class) : array
    {    
        // get all properties (not recursive, one level only)
        $properties = collect(get_object_vars($node))->keys();

        return $properties->map(function ($property) use ($node, $class) {
            // if property holds object
            if (is_object($node->$property) && $node->$property instanceof $class) {
                return $node->$property;
            }
            // if property holds array of objects
            if (is_array($node->$property)) {
                return collect($node->$property)->filter(function ($item) use ($class) {
                    if (is_object($item) && $item instanceof $class) {
                        return $item;
                    }
                })->toArray();
            }
        })->filter()->flatten()->toArray();
    }

    public function findFirstInstanceOf($node, string $class)
    {
        return collect($this->findInstanceOf($node, $class))->first();
    }
}
