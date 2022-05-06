<?php

namespace Archetype;

use Archetype\Endpoints\Laravel\BelongsTo;
use Archetype\Endpoints\Laravel\BelongsToMany;
use Archetype\Endpoints\Laravel\HasMany;
use Archetype\Endpoints\Laravel\HasOne;
use Archetype\Endpoints\Laravel\ModelProperties;
use Archetype\PHPFile;

class LaravelFile extends PHPFile
{
	protected string $fileQueryBuilder = Endpoints\Laravel\LaravelFileQueryBuilder::class;

	public function user(...$args)
	{
		return $this->query()->user(...$args);
	}

	public function controllers(...$args)
	{
		return $this->query()->controllers(...$args);
	}
	
	public function models(...$args)
	{
		return $this->query()->controllers(...$args);
	}	

	public function casts(...$args)
	{
		$handler = new ModelProperties($this);
		return $handler->casts(...$args);
	}
	
	public function connection(...$args)
	{
		$handler = new ModelProperties($this);
		return $handler->connection(...$args);
	}
	
	public function table(...$args)
	{
		$handler = new ModelProperties($this);
		return $handler->table(...$args);
	}
	
	public function dates(...$args)
	{
		$handler = new ModelProperties($this);
		return $handler->dates(...$args);
	}
	
	public function timestamps(...$args)
	{
		$handler = new ModelProperties($this);
		return $handler->timestamps(...$args);
	}
	
	public function visible(...$args)
	{
		$handler = new ModelProperties($this);
		return $handler->visible(...$args);
	}
	
	public function guarded(...$args)
	{
		$handler = new ModelProperties($this);
		return $handler->guarded(...$args);
	}
	
	public function unguarded(...$args)
	{
		$handler = new ModelProperties($this);
		return $handler->unguarded(...$args);
	}
	
	public function fillable(...$args)
	{
		$handler = new ModelProperties($this);
		return $handler->fillable(...$args);
	}
	
	public function hidden(...$args)
	{
		$handler = new ModelProperties($this);
		return $handler->hidden(...$args);
	}

	public function belongsTo($targets)
	{
		$handler = new BelongsTo($this);
		return $handler->belongsTo($targets);
	}

	public function belongsToMany($targets)
	{
		$handler = new BelongsToMany($this);
		return $handler->belongsToMany($targets);
	}

	public function hasMany($targets)
	{
		$handler = new HasMany($this);
		return $handler->hasMany($targets);
	}
	
	public function hasOne($targets)
	{
		$handler = new HasOne($this);
		return $handler->hasOne($targets);
	}
}
