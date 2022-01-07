<?php

namespace Archetype\Support\AST;

use PhpParser\NodeFinder;
use Archetype\Support\AST\ShallowNodeFinder;
use Archetype\Traits\HasOperators;
use Archetype\Traits\PHPParserClassMap;
use Archetype\Support\AST\Killable;
use Archetype\Support\AST\Visitors\NodeReplacer;
use Archetype\Support\AST\Visitors\NodeRemover;
use Archetype\Support\AST\Visitors\HashInserter;
use Archetype\Support\AST\Visitors\StmtInserter;
use Archetype\Support\AST\Visitors\NodePropertyReplacer;
use Archetype\Support\HigherOrderDumper;
use Archetype\Traits\Dumpable;
use Archetype\Traits\Tappable;
use Closure;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use PhpParser\ConstExprEvaluator;

class ASTQueryBuilder
{
    use HasOperators,
		PHPParserClassMap,
		RenderGraphs,
		Dumpable,
		Tappable;

    public $allowDeepQueries = true;

    public $currentDepth = 0;

    public $resultingAST;

    public $parent;

	public $tree;

    public function __construct($ast)
    {
        $this->resultingAST = $ast;

        $this->tree = [
            [new Survivor(
                HashInserter::on($ast)
            )],
        ];
    }

    /**
     * Continue into a Node
     * Example: $query->classMethod() ...
     *
     * @param [string] $method
     * @param [array] $args
     * @return void
     */
    public function __call(string $method, array $args = [])
    {
        // Can we find a corresponding PHPParser class to enter?
        $class = $this->classMap($method);
        if ($class) {
            return $this->traverseIntoClass($class, ...$args);
        }

        throw new Exception("Could not find a method $method in the ASTQueryBuilder!");
    }

    /**
     * Continue into a Node property
     * Example: $query->name ...
     *
     * @param [string] $name
     * @return void
     */
    public function __get(string $name)
    {
        // Can we find a corresponding PHPParser property to enter?
        $property = $this->propertyMap($name);
        if ($property) return $this->traverseIntoProperty($property);

		if($name == 'dd') return HigherOrderDumper::dd($this);
		if($name == 'dump') return HigherOrderDumper::dump($this);
		if($name == 'where') return new HigherOrderWhere($this);

        throw new Exception("Could not find a property $property in the ASTQueryBuilder!");
    }

    public function traverseIntoClass($expectedClass, $path = null, $finderMethod = 'findInstanceOf')
    {
		$steps = $path ? collect(explode('->', $path)) : collect();

        return $this->next(function ($queryNode) use ($expectedClass, $finderMethod, $steps) {
            $classMatches = $this->nodeFinder()->$finderMethod($queryNode->result, $expectedClass);

			$classAndPathMatches = collect($classMatches)->map(function ($node) use($steps) {
				return $steps->reduce(function ($result, $step) {
					$hasPath = is_object($result) && isset($result->$step) && $result->$step;
					return $hasPath ? $result->$step : null;
				}, $node);                
            })->filter();
			
            return $classAndPathMatches->map(function ($result) use ($queryNode) {
                return Survivor::fromParent($queryNode)->withResult($result);
            })->toArray();
        });
    }

    // public function traverseIntoClass($expectedClass, $finderMethod = 'findInstanceOf')
    // {
    //     return $this->next(function ($queryNode) use ($expectedClass, $finderMethod) {
    //         // Search the abstract syntax tree
    //         $results = $this->nodeFinder()->$finderMethod($queryNode->result, $expectedClass);
    //         // Wrap matches in Survivor object
    //         return collect($results)->map(function ($result) use ($queryNode) {
    //             return Survivor::fromParent($queryNode)->withResult($result);
    //         })->toArray();
    //     });
    // }	

    public function traverseIntoProperty($property)
    {
        return $this->next(function ($queryNode) use ($property) {
			$results = Arr::wrap($queryNode->result);
			$values = collect($results)->map(function($result) use($property) {
				return $result->$property ?? null;
			})->filter()->flatten();

			return $values->map(function ($value) use ($queryNode) {
				return Survivor::fromParent($queryNode)->withResult($value);
			});
        });
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

    public function remember($key, $callback)
    {
        $this->currentNodes()->each(function ($queryNode) use ($key, $callback) {
            
            if ($queryNode instanceof Killable) {
                return;
            }
            
            $queryNode->memory[$key] = $callback($queryNode->result);
        });

        return $this;
    }

	public function is($expected)
	{
		return $this->whereEquals($expected);
	}

	public function whereEquals($expected)
	{
        return $this->next(function ($queryNode) use ($expected) {
            $nodes = collect(Arr::wrap($queryNode->result));

			return $nodes->map(function($node) use($expected, $queryNode) {
				return $node === $expected
					? Survivor::fromParent($queryNode)->withResult($node)
					: new Killable;
			});
        });
	}

    public function where($arg1, $arg2 = null)
    {
        return $arg1 instanceof Closure ? $this->whereCallback($arg1) : $this->wherePath($arg1, $arg2);
    }

    public function next($callback)
    {
        $next = $this->currentNodes()->map($callback)->flatten()->toArray();

        array_push($this->tree, $next);

        $this->currentDepth++;

        return $this;
    }

    protected function nodeFinder()
    {
        return $this->allowDeepQueries ? new NodeFinder : new ShallowNodeFinder;
    }

    protected function wherePath($path, $expected)
    {
        return $this->next(function ($queryNode) use ($path, $expected) {
            $nodes = collect(Arr::wrap($queryNode->result));
			$steps = collect(explode('->', $path));

			return $nodes->map(function($node) use($steps, $expected, $queryNode) {
				$actual = $steps->reduce(function ($result, $step) {
					return is_object($result) && isset($result->$step) ? $result->$step : new Killable;
				}, $node);

				return $actual == $expected
					? Survivor::fromParent($queryNode)->withResult($node)
					: new Killable;
			});
        });
    }

    protected function whereCallback($callback)
    {
        return $this->next(function ($queryNode) use ($callback) {
            $query = new static(
                Arr::wrap((clone $queryNode)->result)
            );

            return $this->whereClauseCallbackIsFulfilled($callback($query))
				? $queryNode
				: new Killable;
        });
    }

	protected function whereClauseCallbackIsFulfilled($result)
	{
		if($result instanceof ASTQueryBuilder) return $result->isNotEmpty();

		if($result instanceof Collection) return $result->isNotEmpty();

		return $result;
	}

    /**
     * Recall data in memory
     * Use this method in conjunction with remember()
     *
     * @param [string] $pluck
     * @return void
     */
    public function recall($pluck = null)
    {
        $memory = collect(end($this->tree))->filter(function ($item) {
            return $item->result;
        })->map->memory;

        return $pluck ? $memory->pluck($pluck) : $memory;
    }

    public function get()
    {
        return collect(end($this->tree))->pluck('result')->flatten();
    }

	public function isNotEmpty()
	{
		return $this->get()->isNotEmpty();
	}

    public function first()
    {
        return $this->get()->first();
    }

    public function getEvaluated()
    {
        return $this->get()->map(function ($item) {
            return (new ConstExprEvaluator())->evaluateSilently($item);
        });
    }

    public function remove()
    {
        $this->currentNodes()->each(function ($node) {
            
            if (!isset($node->result->__object_hash)) {
                return;
            }
            
            $this->resultingAST = NodeRemover::remove(
                $node->result->__object_hash,
                $this->resultingAST
            );
        });
        
        return $this;
    }

    /**
     * Replace node
     *
     * @param Node|Closure $arg1
     * @return $this
     */
    public function replace($arg1)
    {
        return is_callable($arg1) ? $this->replaceWithCallback($arg1) : $this->replaceWithNode($arg1);
    }

    protected function replaceWithCallback(Closure $callback)
    {
        $this->currentNodes()->each(function ($node) use ($callback) {
            if (!isset($node->result->__object_hash)) {
                return;
            }

            $this->resultingAST = NodeReplacer::replace(
                $node->result->__object_hash,
                $callback($node->result),
                $this->resultingAST
            );
        });

        return $this;
    }

    protected function replaceWithNode($newNode)
    {
        $this->currentNodes()->each(function ($node) use ($newNode) {
            
            $target = $node->result;

            if (!$target) {
                return;
            }

            $this->resultingAST = NodeReplacer::replace(
                $target->__object_hash,
                $newNode,
                $this->resultingAST
            );
        });

        return $this;
    }

    public function replaceProperty($key, $value)
    {
        $this->currentNodes()->each(function ($node) use ($key, $value) {
            if (!isset($node->result->__object_hash)) {
                return;
            }

            $this->resultingAST = NodePropertyReplacer::replace(
                $node->result->__object_hash,
                $key,
                $value,
                $this->resultingAST
            );
        });

        return $this;
    }

    public function insertStmts($newNodes)
    {
        collect($newNodes)->each(function ($newNode) {
            $this->insertStmt($newNode);
        });

        return $this;
    }

    public function insertStmt($newNode)
    {
        $this->currentNodes()->each(function ($node) use ($newNode) {

            $target = $node->result;

            // Assume insertion targets namespace stmts (if present at index 0)
            if (is_array($target) && !empty($target) && get_class($target[0]) == 'PhpParser\\Node\\Stmt\Namespace_') {
                $target = $target[0];
            }

            $this->resultingAST = StmtInserter::insertStmt(
                $target->__object_hash ?? null,
                $newNode,
                $this->resultingAST
            );
        });

        return $this;
    }
    
    public function commit()
    {
        $this->parent->ast(
            $this->resultingAST
        );

        return $this;
    }

    public function end()
    {
        return $this->parent;
    }

    public function currentNodes()
    {
        return collect($this->tree[$this->currentDepth]);
    }
}
