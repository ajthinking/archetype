<?php

namespace PHPFileManipulator\Support\AST;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;
use InvalidArgumentException;
use LaravelFile;
use PhpParser\NodeFinder;
use PHPFileManipulator\Traits\HasOperators;
use PHPFileManipulator\Support\AST\Killable;
use PHPFileManipulator\Support\AST\RemovedNode;
use PHPFileManipulator\Support\AST\ASTTraverser;
use PHPFileManipulator\Support\AST\NodeReplacer;
use PHPFileManipulator\Support\AST\HashInserter;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;

class ASTTraverser
{
    const UNTIL = [
        'class' => \PhpParser\Node\Stmt\Class_::class,
        'closure' => \PhpParser\Node\Expr\Closure::class,
        'const' => \PhpParser\Node\Stmt\Const_::class,
        'function' => \PhpParser\Node\Stmt\Function_::class,
        'method' => \PhpParser\Node\Stmt\ClassMethod::class,
        'methodCall' => \PhpParser\Node\Expr\MethodCall::class,
        'staticCall' => \PhpParser\Node\Expr\StaticCall::class,
        'string' => \PhpParser\Node\Scalar\String_::class,
    ];

    public function traverse($expectedClass, $finderMethod = 'findInstanceOf')
    {
        $next = collect($this->tree[$this->depth])->map(function($queryNode) use($expectedClass, $finderMethod) {
            // Search the abstract syntax tree
            $results = (new NodeFinder)->$finderMethod($queryNode->results, $expectedClass);
            // Wrap matches in Survivor object
            return collect($results)->map(function($result) use($queryNode) {
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

    public function traverseIntoArrayIndex($property, $index)
    {
        $next = collect($this->tree[$this->depth])->map(function($queryNode) use($property, $index) {
            if(!isset($queryNode->results->$property)) return new Killable;
            return Survivor::fromParent($queryNode)->withResult(
                $queryNode->results->$property[$index]
            );
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
}