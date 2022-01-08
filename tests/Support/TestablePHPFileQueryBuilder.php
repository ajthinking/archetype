<?php

namespace Archetype\Tests\Support;

use Archetype\Endpoints\PHP\PHPFileQueryBuilder;
use Illuminate\Support\Collection;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

class TestablePHPFileQueryBuilder extends PHPFileQueryBuilder
{
	public function assertMatchCount($count)
	{
		assertEquals($count, $this->get()->count());

		return $this;
	}

	public function assertInstanceOf($class)
	{
		assertInstanceOf($class, $this);

		return $this;
	}

	public function assertMatches(Collection $expected)
	{
		assertEquals($expected, $this->get());

		return $this;
	}	
}
