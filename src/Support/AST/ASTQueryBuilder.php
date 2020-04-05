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
use PHPFileManipulator\Traits\PHPParserClassMap;
use PHPFileManipulator\Support\AST\Killable;
use PHPFileManipulator\Support\AST\RemovedNode;
use PHPFileManipulator\Support\AST\NodeReplacer;
use PHPFileManipulator\Support\AST\HashInserter;
use PhpParser\Node\Stmt\Use_;
use Exception;

class ASTQueryBuilder
{
    use HasOperators;
    
    use PHPParserClassMap;

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

    public function __call($method, $args)
    {
        // exists in classMap?
        if($this->classMap($method)) return $this->traverse($this->classMap($method));        

        throw new Exception("Could not find a method $method in the ASTQueryBuilder!");
    }

    public function __get($property)
    {
        // exists in propertyMap?
        if($this->propertyMap($property)) return $this->traverseInto($this->propertyMap($property));        

        throw new Exception("Could not find a property $property in the ASTQueryBuilder!");
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


    /** OUTSIDE CONVENTION */
    public function expression()
    {
        return $this->traverse(
            $this->classMap('expression')
        )->traverseInto('expr');
    }    

    /** OUTSIDE CONVENTION */
    public function named($string)
    {
        return $this->where('name->name', $string);
    }

    /** OUTSIDE CONVENTION */
    public function arg($index)
    {
        return $this->traverseIntoArrayIndex('args', $index);
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
    
    public function dd()
    {
        dd($this->get());
    }    
}