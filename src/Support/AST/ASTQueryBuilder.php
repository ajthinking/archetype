<?php

namespace PHPFileManipulator\Support\AST;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;
use InvalidArgumentException;
use LaravelFile;
use PhpParser\NodeFinder;
use PHPFileManipulator\Support\AST\ShallowNodeFinder;
use PHPFileManipulator\Traits\HasOperators;
use PHPFileManipulator\Support\AST\Killable;
use PHPFileManipulator\Support\AST\RemovedNode;
use PHPFileManipulator\Support\AST\NodeReplacer;
use PHPFileManipulator\Support\AST\HashInserter;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;

class ASTQueryBuilder
{
    use HasOperators;

    public $allow_deep_queries = true;
    
    protected const classMap = [
        'class' => \PhpParser\Node\Stmt\Class_::class,
        'closure' => \PhpParser\Node\Expr\Closure::class,
        'const' => \PhpParser\Node\Stmt\Const_::class,
        'function' => \PhpParser\Node\Stmt\Function_::class,
        'method' => \PhpParser\Node\Stmt\ClassMethod::class,
        'methodCall' => \PhpParser\Node\Expr\MethodCall::class,
        'staticCall' => \PhpParser\Node\Expr\StaticCall::class,
        'string' => \PhpParser\Node\Scalar\String_::class,
    ];


    public function __construct($ast)
    {
        $this->ast = $ast;

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new HashInserter);
        $this->ast = $traverser->traverse($ast);

        $this->depth = 0;
        $this->tree = [
            [new Survivor($this->ast)],
        ];
    }

    public function traverse($expectedClass, $finderMethod = 'findInstanceOf')
    {
        $next = collect($this->tree[$this->depth])->map(function($queryNode) use($expectedClass, $finderMethod) {
            // Search the abstract syntax tree
            $results = $this->nodeFinder()->$finderMethod($queryNode->results, $expectedClass);
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
            static::classMap[$class],
            'findFirstInstanceOf'
        );        
    }   

    public function shallow()
    {
        $this->allow_deep_queries = false;
        return $this;
    }

    public function deep()
    {
        $this->allow_deep_queries = true;
        return $this;
    }

    protected function nodeFinder()
    {
        return $this->allow_deep_queries ? new NodeFinder : new ShallowNodeFinder;
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
            static::classMap['class']
        );
    }
    
    public function const()
    {
        return $this->traverse(
            static::classMap['const']
        );
    }    

    public function method()
    {
        return $this->traverse(
            static::classMap['method']
        );
    }

    public function methodCall()
    {
        return $this->traverse(
            static::classMap['methodCall']
        );
    }

    public function staticCall()
    {
        return $this->traverse(
            static::classMap['staticCall']
        );
    }
    
    public function string()
    {
        return $this->traverse(
            static::classMap['string']
        );        
    }
    
    public function closure()
    {
        return $this->traverse(
            static::classMap['closure']
        );
    }
    
    public function function()
    {
        return $this->traverse(
            static::classMap['function']
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

    public function getChain()
    {
        return [
            'm1' => ['p1', 'p2', 'p3'],
            'm2' => ['p1', 'p2'],
        ];
    }

    public function dd()
    {
        dd($this->get());
    }
    
    public function ddFirst()
    {
        return dd(
            $this->get()[0] ?? "NO RESULTS AVAILABLE"
        );
    }    
}