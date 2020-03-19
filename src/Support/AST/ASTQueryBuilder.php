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
use PHPFileManipulator\Support\AST\ASTTraverser;
use PHPFileManipulator\Support\AST\NodeReplacer;
use PHPFileManipulator\Support\AST\HashInserter;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;

class ASTQueryBuilder extends ASTTraverser
{
    use HasOperators;

    public function __construct($ast)
    {
        $this->ast = $ast;

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new HashInserter);
        $this->ast = $traverser->traverse($ast);

        $this->manipulations = [];
        $this->depth = 0;
        $this->tree = [
            [new Survivor($this->ast)],
        ];
    }

    public function remember($key, $callback)
    {
        collect($this->tree[$this->depth])->each(function($queryNode) use($key, $callback) {
            $queryNode->memory[$key] = $callback((clone $queryNode)->results);
        });

        return $this;
    }

    public function class()
    {
        return $this->traverse(
            static::UNTIL['class']
        );
    }
    
    public function const()
    {
        return $this->traverse(
            static::UNTIL['const']
        );
    }    

    public function method()
    {
        return $this->traverse(
            static::UNTIL['method']
        );
    }

    public function methodCall()
    {
        return $this->traverse(
            static::UNTIL['methodCall']
        );
    }

    public function staticCall()
    {
        return $this->traverse(
            static::UNTIL['staticCall']
        );
    }
    
    public function string()
    {
        return $this->traverse(
            static::UNTIL['string']
        );        
    }
    
    public function closure()
    {
        return $this->traverse(
            static::UNTIL['closure']
        );
    }
    
    public function function()
    {
        return $this->traverse(
            static::UNTIL['function']
        );
    }    

    public function named($string)
    {
        return $this->where('name->name', $string);
    }

    public function arg($index)
    {
        return $this->traverseIntoArrayIndex('args', $index);
    }

    public function args()
    {
        return $this->traverseInto('args');
    }

    public function stmts()
    {
        return $this->traverseInto('stmts');
    }

    public function value()
    {
        return $this->traverseInto('value');
    }
    
    public function name()
    {
        return $this->traverseInto('name');
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
        $nextLevel = collect($this->tree[$this->depth])->map(function($queryNode) use($path, $expected) {
            $steps = collect(explode('->', $path));
            $result = $steps->reduce(function($result, $step) {
                return is_object($result) && isset($result->$step) ? $result->$step : new Killable;
            }, $queryNode->results);
            return $result == $expected ? $queryNode : new Killable;
        })->flatten()->toArray();

        array_push($this->tree, $nextLevel);
        $this->depth++;

        return $this;
    }

    public function recall()
    {
        return collect(end($this->tree))->filter(fn($item) => $item->results)->map(function($item) {
            return (object) $item->memory;
        });
    }

    public function get()
    {
        return collect(end($this->tree))->pluck('results')->flatten();
    }

    public function dd()
    {
        return dd(
            $this->get()
        );
    }    
}