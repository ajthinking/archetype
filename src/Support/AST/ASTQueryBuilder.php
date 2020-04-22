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
        // Can we find a corresponding PHPParser class to enter?
        $class = $this->classMap($method);
        if($class) return $this->traverseIntoClass($class);        

        throw new Exception("Could not find a method $method in the ASTQueryBuilder!");
    }

    public function __get($name)
    {
        // Can we find a corresponding PHPParser property to enter?
        $property = $this->propertyMap($name);
        if($property) return $this->traverseIntoProperty($property);        

        throw new Exception("Could not find a property $property in the ASTQueryBuilder!");
    }    

    public function traverseIntoClass($expectedClass, $finderMethod = 'findInstanceOf')
    {
        $next = $this->currentNodes()->map(function($queryNode) use($expectedClass, $finderMethod) {
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

    public function traverseIntoProperty($property)
    {
        $next = $this->currentNodes()->map(function($queryNode) use($property) {
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
        $this->currentNodes()->each(function($queryNode) use($key, $callback) {

            $subAST = [(clone $queryNode)->results];
            $subQueryBuilder = new static($subAST);

            $queryNode->memory[$key] = $callback($subQueryBuilder);
        });

        return $this;
    }

    public function where($path, $expected)
    {
        $nextLevel = $this->currentNodes()->map(function($queryNode) use($path, $expected) {
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
        $nextLevel = $this->currentNodes()->map(function($queryNode) use($name) {
            $current = $queryNode->results;
            do {
                $current = $current->var ?? false;
            } while($current && '\\' . get_class($current) == $this->classMap('methodCall'));

            return $current->name == $name ? $queryNode : new Killable;
        })->flatten()->toArray();

        array_push($this->tree, $nextLevel);
        $this->currentDepth++;

        return $this;
    }

    public function flattenChain()
    {
        $flattened = $this->currentNodes()->map(function($queryNode) {
            $results = collect();
            $current = $queryNode->results[0];

            do {
                $results->push($current);
                $current = $current->var ?? false;
                
            } while($current && '\\' . get_class($current) == $this->classMap('methodCall'));

            return $results->reverse();
            
        })->flatten();

        return $flattened->flatMap(function($methodCall) {
            $var = $methodCall->var->name;
            $name = $methodCall->name;
            $args = $methodCall->args;

            return [
                $methodCall->name->name => collect($args)->map(function($arg) {
                    return $arg->value->value;
                })->values()->toArray()
            ];
        })->toArray();
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

    public function replace($newNode)
    {
        return $this;
    }

    protected function currentNodes()
    {
        return collect($this->tree[$this->currentDepth]);
    }
    
    public function dd()
    {
        dd($this->get());
    }
}