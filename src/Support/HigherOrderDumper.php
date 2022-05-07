<?php

namespace Archetype\Support;

class HigherOrderDumper
{
	protected $target;

	protected string $dumper;

	final public function __construct($target, $dumper)
	{
		$this->target = $target;
		$this->dumper = $dumper;
	}

	public static function dd($target)
	{
		return new static($target, 'dd');
	}

	public static function dump($target)
	{
		return new static($target, 'dump');
	}	

    public function __call(string $method, array $args = [])
    {
		$handler = $this->dumper;
		$handler($this->target->$method(...$args));

		return $this->target;
    }

    public function __get(string $name)
	{
		dd($this->target->$name);
	}
}