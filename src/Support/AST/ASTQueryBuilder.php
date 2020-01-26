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
use PHPFileManipulator\Support\AST\RemovedNode;
use PHPFileManipulator\Support\AST\Traversable;
use PHPFileManipulator\Support\AST\NodeReplacer;
use PHPFileManipulator\Support\AST\SplObjectHashInserter;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;

class ASTQueryBuilder extends Traversable
{
    use HasOperators;

    public function __construct($ast)
    {
        $this->ast = $ast;
        $traverser = new NodeTraverser();
        $visitor = new SplObjectHashInserter;
        $traverser->addVisitor($visitor);
        $this->initial = $traverser->traverse($this->ast);

        $this->manipulations = [];
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

    public function traverseInto($property)
    {
        $next = collect($this->tree[$this->depth])->map(function($item) use($property) {
            return $item->$property ?? new Killable;
        })->filter(function($results) {
            return !Terminator::kills($results);
        })->flatten()->toArray();

        array_push($this->tree, $next);
        
        $this->depth++;

        return $this;
    }

    public function traverseIntoArray($index)
    {
        $next = collect($this->tree[$this->depth])->map(function($item) use($index) {
            return $item[$index] ?? new Killable;
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

    public function staticCall()
    {
        return $this->traverse(
            static::UNTIL['staticCall']
        );
    }    

    public function named($string)
    {
        return $this->where('name->name', $string);
    }

    public function args()
    {
        return $this->traverseInto('args');
    }

    public function value()
    {
        return $this->traverseInto('value');
    }    
    
    public function first()
    {
        return $this->traverseIntoArray(0);
    }
    
    public function second()
    {
        return $this->traverseIntoArray(1);
    }
    
    public function third()
    {
        return $this->traverseIntoArray(2);
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
        return collect(end($this->tree));
    }
}