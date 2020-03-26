<?php

namespace PHPFileManipulator\Support\AST;

use PhpParser\NodeFinder;

class ShallowNodeFinder extends NodeFinder {

    public function findInstanceOf($nodes, string $class) : array {
        return collect($nodes)->filter(function ($node) use ($class) {
            return $node instanceof $class;
        })->toArray();
    }

    public function findFirstInstanceOf($nodes, string $class) {
        return collect($nodes)->find(function ($node) use ($class) {
            return $node instanceof $class;
        });
    }    
}