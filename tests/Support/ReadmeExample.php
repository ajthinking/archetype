<?php

namespace Archetype\Tests\Support;

use function PHPUnit\Framework\assertEquals;
use Illuminate\Support\Str;

class ReadmeExample
{
	public string $code;
	public string $output;

	public function __construct($code, $output)
	{
		$this->code = $code;
		$this->output = $output;
	}

	public function assertCodeReturnsOutput()
	{
		$expected = $this->output;
		$actual = eval($this->preparedCode());
		
		assertEquals($expected, $actual);

		return $this;
	}

	protected function preparedCode()
	{
		return Str::of($this->code)
			->prepend('return ')
			->append(';')
			->replace(
				'PHPFile',
				'\Archetype\Facades\PHPFile'

			);
	}
}