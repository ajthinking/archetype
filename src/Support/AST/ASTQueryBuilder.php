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
use PHPFileManipulator\Support\AST\HashInserter;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;

class ASTQueryBuilder extends Traversable
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

    public function traverse($expectedClass, $finderMethod = 'findInstanceOf')
    {
        $next = collect($this->tree[$this->depth])->map(function($queryNode) use($expectedClass, $finderMethod) {
            // Search the abstract syntax tree
            $results = (new NodeFinder)->$finderMethod($queryNode->results, $expectedClass);
            // Wrap matches in Survivor object
            return collect($results)->map(function($result, $queryNode) {
                return Survivor::fromParent($queryNode)->withResult($result);
            })->toArray();
        })->flatten()->toArray();
        
        array_push($this->tree, $next);

        $this->depth++;

        return $this;        
    }

    public function traverseInto($property)
    {
        $next = collect($this->tree[$this->depth])->map(function($queryNode) use($property) {
            if(!isset($queryNode->results->$property)) return new Killable;
            $value = $queryNode->results->$property;
            
            if(is_array($value)) {
                return collect($value)->map(function($item) use($value, $queryNode) {
                    return Survivor::fromParent($queryNode)->withResult($item);
                })->toArray();
            }

            return Survivor::fromParent($queryNode)->withResult($value);
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

    public function remember($key, $callback)
    {
        collect($this->tree[$this->depth])->each(function($queryNode) use($key, $callback) {
            $queryNode->memory[$key] = $callback($queryNode);
        });

        return $this;
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
    
    public function closure()
    {
        return $this->traverse(
            static::UNTIL['closure']
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
        collect(end($this->tree))->pluck('memory')->flatten();
    }

    public function get()
    {
        return collect(end($this->tree))->pluck('results')->flatten();
    }

    public function dd()
    {
        return collect(end($this->tree));
    }    
}