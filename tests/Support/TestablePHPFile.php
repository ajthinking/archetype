<?php

namespace Archetype\Tests\Support;

use Archetype\PHPFile;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertStringContainsString;

class TestablePHPFile extends PHPFile
{
	public function assertClassConstant(string $name, $value)
	{
		assertEquals($this->classConstant($name), $value);

		return $this;
	}

	public function assertNoClassConstant(string $name)
	{
		$exists = $this->astQuery()
            ->class()
            ->classConst()
            ->where(function ($query) use ($name) {
                return $query->const()
					->where('name->name', $name)
                    ->get()
					->isNotEmpty();
            })->get()
			->isNotEmpty();

		assertFalse($exists);

		return $this;
	}

	public function assertNoProperty(string $name)
	{
		$exists = $this->astQuery()
            ->class()
            ->property()
            ->where(function ($query) use ($name) {
                return $query->propertyProperty()
					->where('name->name', $name)
                    ->get()
					->isNotEmpty();
            })->get()
			->isNotEmpty();

		assertFalse($exists);

		return $this;
	}

	public function assertProperty($name, $value)
	{
		assertEquals($this->property($name), $value);
		
		return $this;
	}

	public function assertContains(string $string)
	{
		assertStringContainsString(
			$string,
			$this->render()
		);

		return $this;
	}
}
