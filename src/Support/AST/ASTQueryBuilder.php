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
use PHPFileManipulator\Traits\HasClassMap;
use PHPFileManipulator\Support\AST\Killable;
use PHPFileManipulator\Support\AST\RemovedNode;
use PHPFileManipulator\Support\AST\NodeReplacer;
use PHPFileManipulator\Support\AST\HashInserter;
use PhpParser\Node\Stmt\Use_;

class ASTQueryBuilder
{
    use HasOperators;
    
    use HasClassMap;

    public $allowDeepQueries = true;

    public $currentDepth = 0;

    public function __construct($ast)
    {
        $this->tree = [
            [new Survivor(
                HashInserter::on($ast)
            )],
        ];
    }

    public function traverse($expectedClass, $finderMethod = 'findInstanceOf')
    {
        $next = collect($this->tree[$this->currentDepth])->map(function($queryNode) use($expectedClass, $finderMethod) {
            // Search the abstract syntax tree
            $results = $this->nodeFinder()->$finderMethod($queryNode->results, $expectedClass);
            // Wrap matches in Survivor object
            return collect($results)->map(function($result) use($queryNode) {
                return Survivor::fromParent($queryNode)->withResult($result);
            })->toArray();
        })->flatten()->toArray();
        
        array_push($this->tree, $next);

        $this->currentDepth++;

        return $this;        
    }

    public function traverseInto($property)
    {
        $next = collect($this->tree[$this->currentDepth])->map(function($queryNode) use($property) {
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

        $this->currentDepth++;

        return $this;
    }

    public function traverseIntoArrayIndex($property, $index)
    {
        $next = collect($this->tree[$this->currentDepth])->map(function($queryNode) use($property, $index) {
            if(!isset($queryNode->results->$property)) return new Killable;
            return Survivor::fromParent($queryNode)->withResult(
                $queryNode->results->$property[$index]
            );
        })->flatten()->toArray();

        array_push($this->tree, $next);

        $this->currentDepth++;

        return $this;
    }    

    public function traverseFirst($class)
    {
        return $this->traverse(
            $this->classMap($class),
            'findFirstInstanceOf'
        );        
    }   

    public function shallow()
    {
        $this->allowDeepQueries = false;
        return $this;
    }

    public function deep()
    {
        $this->allowDeepQueries = true;
        return $this;
    }

    protected function nodeFinder()
    {
        return $this->allowDeepQueries ? new NodeFinder : new ShallowNodeFinder;
    }

    public function remember($key, $callback)
    {
        collect($this->tree[$this->currentDepth])->each(function($queryNode) use($key, $callback) {
            $queryNode->memory[$key] = $callback((clone $queryNode)->results);
        });

        return $this;
    }

    public function class()
    {
        return $this->traverse(
            $this->classMap('class')
        );
    }

    public function expression()
    {
        return $this->traverse(
            $this->classMap('expression')
        )->traverseInto('expr');
    }
    
    public function const()
    {
        return $this->traverse(
            $this->classMap('const')
        );
    }    

    public function method()
    {
        return $this->traverse(
            $this->classMap('method')
        );
    }

    public function methodCall()
    {
        return $this->traverse(
            $this->classMap('methodCall')
        );
    }

    public function staticCall()
    {
        return $this->traverse(
            $this->classMap('staticCall')
        );
    }
    
    public function string()
    {
        return $this->traverse(
            $this->classMap('string')
        );        
    }
    
    public function closure()
    {
        return $this->traverse(
            $this->classMap('closure')
        );
    }
    
    public function function()
    {
        return $this->traverse(
            $this->classMap('function')
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
        $nextLevel = collect($this->tree[$this->currentDepth])->map(function($queryNode) use($path, $expected) {
            $steps = collect(explode('->', $path));
            $result = $steps->reduce(function($result, $step) {
                return is_object($result) && isset($result->$step) ? $result->$step : new Killable;
            }, $queryNode->results);
            return $result == $expected ? $queryNode : new Killable;
        })->flatten()->toArray();

        array_push($this->tree, $nextLevel);
        $this->currentDepth++;

        return $this;
    }

    public function whereChainingOn($name)
    {
        $nextLevel = collect($this->tree[$this->currentDepth])->map(function($queryNode) use($name) {
            $current = $queryNode->results;
            do {
                $current = $current->var ?? dd($current);
            } while($current && '\\' . get_class($current) == $this->classMap('methodCall'));

            return $current->name == $name ? $queryNode : new Killable;
        })->flatten()->toArray();

        array_push($this->tree, $nextLevel);
        $this->currentDepth++;

        return $this;
    }

    public function flatten()
    {
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