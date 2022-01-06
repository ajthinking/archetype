<?php

namespace Archetype\Tests\Support;

use Archetype\PHPFile;
use Archetype\Support\AST\ASTQueryBuilder;
use Illuminate\Support\Collection;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertMatchesRegularExpression;
use function PHPUnit\Framework\assertStringContainsString;

class TestableASTQueryBuilder extends ASTQueryBuilder
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
