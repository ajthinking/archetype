<?php

namespace Archetype\Tests\Support;

use Archetype\PHPFile;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertMatchesRegularExpression;
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

	public function assertValidPhp()
	{
		assertInstanceOf(
			TestablePHPFile::class,
			PHPFile::fromString($this->render())
		);

		return $this;
	}

	public function assertMultilineArray($name) {
		preg_match("/$name \= (\[[^\;]*)/s", $this->render(), $matches);
		$code = $matches[1];
		$commas = substr_count($code, ',');
		
		assertEquals(
			substr_count($code, PHP_EOL),
			$commas + 1
		);

		return $this;
	}

	public function assertSingleLineEmptyArray($name) {
		assertMatchesRegularExpression("/$name \= (\[\];]*)/s", $this->render());

		return $this;		
	}	
}
