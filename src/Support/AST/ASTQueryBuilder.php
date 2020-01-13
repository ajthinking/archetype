<?php

namespace PHPFileManipulator\Support\AST;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;
use InvalidArgumentException;
use LaravelFile;
use PhpParser\NodeFinder;
use PHPFileManipulator\Traits\HasOperators;
use PHPFileManipulator\Support\AST\Terminator;
use PHPFileManipulator\Support\AST\Killable;
use PHPFileManipulator\Support\AST\Traversable;

class ASTQueryBuilder extends Traversable
{
    use HasOperators;

    public function __construct($ast)
    {
        $this->initial = $ast;
        $this->depth = 0;
        $this->tree = [
            [$this->initial],
        ];
    }

    public function traverse($expectedClass, $finderMethod = 'findInstanceOf')
    {
        $next = collect($this->tree[$this->depth])->map(function($item) use($expectedClass, $finderMethod) {
            $results = (new NodeFinder)->$finderMethod($item, $expectedClass);
            return $results ? $results : new Killable;
        })->filter(function($results) {
            return !Terminator::kills($results);
        })->flatten()->toArray();

        array_push($this->tree, $next);
        
        $this->depth++;

        return $this;        
    }

    public function traverseFirst($class)
    {
        return $this->traverse(
            static::UNTIL[$class],
            'findFirstInstanceOf'
        );        
    }

    public function class()
    {
        return $this->traverse(
            static::UNTIL['class']
        );
    }    

    public function method()
    {
        return $this->traverse(
            static::UNTIL['method']
        );
    }

    public function where($path, $expected)
    {
        $nextLevel = collect($this->tree[$this->depth])->map(function($item) use($path, $expected) {
            $steps = collect(explode('->', $path));
            $result = $steps->reduce(function($result, $step) {
                return is_object($result) && isset($result->$step) ? $result->$step : new Killable;
            }, $item);

            return $result == $expected ? $item : new Killable;
        })->filter(function($results) {
            return !Terminator::kills($results);
        })->flatten()->toArray();

        array_push($this->tree, $nextLevel);
        $this->depth++;

        return $this;
    }
    
    public function get()
    {
        return end($this->tree);
    }
}