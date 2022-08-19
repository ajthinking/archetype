<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use PhpParser\BuilderFactory;
use Illuminate\Support\Arr;

class UseTrait extends EndpointProvider
{
	/**
	 * @example Get which traits a class uses
	 * @source $file->useTrait()
	 */
	public function useTrait($value = null)
	{
		if ($this->file->directive('add')) {
			return $this->add($value);
		}

		if ($value === null) {
			return $this->get();
		}

		return $this->set($value);
	}

	protected function get()
	{
		$r = $this->file->astQuery()
			->class()
			->traitUse()
			->name()
			->parts
			->get()
			->toArray();

		return $r;
	}

	protected function add($newUseTraitNames)
	{
		return $this->file->astQuery()
			->class()
			->insertStmts(
				collect(Arr::wrap($newUseTraitNames))
					->reverse()
					->map(function ($name) {
						return $this->getUseTraitNode($name);
					})->toArray()
			)
			->commit()
			->end()
			->continue();
	}

	protected function getUseTraitNode($name)
	{
		$factory = new BuilderFactory;
		return $factory->useTrait($name)->getNode();
	}

	protected function set($newUseTraitNames)
	{
		$this->file->astQuery()
			->traitUse()
			->remove()
			->commit();

		$this->add($newUseTraitNames);

		return $this->file;
	}
}
