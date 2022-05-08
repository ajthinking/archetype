<?php

namespace Archetype\Support\AST;

use Illuminate\Support\Arr;

class HigherOrderWhere
{
	protected $target;

	protected $subQuery;

	protected $stack = [];

	public function __construct($target)
	{
		$this->target = $target;
	}	

    public function __call(string $method, array $args = [])
    {
		array_push($this->stack, ['__call', $method, $args]);
		
		return $this;
    }

    public function __get(string $name)
	{
		array_push($this->stack, ['__name', $name]);	

		return $this;
	}

    public function get()
	{
		$this->target->next(function ($queryNode) {
			$subQuery = $this->getSubQuery($queryNode);
			return $this->applyStack($subQuery)->isNotEmpty()
				? $queryNode
				: new Killable;
		});

		return $this->target;
	}

    public function isNotEmpty()
	{
		return $this->get();
	}
	
	protected function getSubQuery($queryNode)
	{
		return new ASTQueryBuilder(
			Arr::wrap((clone $queryNode)->result)
		);
	}

	protected function applyStack($query)
	{
		foreach($this->stack as $item) {
			if($item[0] === '__call') {
				$method = $item[1];
				$args = $item[2];
				$query->$method(...$args);
			} else {
				$property = $item[1];
				$query->$property;
			}
		}

		return $query;
	}
}