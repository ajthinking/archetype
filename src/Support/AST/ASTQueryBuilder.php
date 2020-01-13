<?php

namespace PHPFileManipulator\Support\AST;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;
use InvalidArgumentException;
use LaravelFile;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPFileManipulator\Traits\HasOperators;
use PHPFileManipulator\Support\AST\Terminator;
use PHPFileManipulator\Support\AST\Killable;

class ASTQueryBuilder
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

    public function class()
    {
        $nextLevel = collect($this->tree[$this->depth])->map(function($item) {
            $classes = (new NodeFinder)->findInstanceOf($item, Class_::class);
            return $classes ? $classes : new Killable;
        })->filter(function($results) {
            return !Terminator::kills($results);
        })->flatten()->toArray();

        array_push($this->tree, $nextLevel);
        
        $this->depth++;

        return $this;
    }

    public function method()
    {
        $nextLevel = collect($this->tree[$this->depth])->map(function($item) {
            $classes = (new NodeFinder)->findInstanceOf($item, ClassMethod::class);
            return $classes ? $classes : new Killable;
        })->filter(function($results) {
            return !Terminator::kills($results);
        })->flatten()->toArray();

        array_push($this->tree, $nextLevel);
        $this->depth++;

        return $this;
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