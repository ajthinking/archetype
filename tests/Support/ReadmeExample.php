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
		$message = "Expected: $expected".PHP_EOL.PHP_EOL."Got: $actual".PHP_EOL.PHP_EOL;

		assertEquals($expected, $actual, $message);

		return $this;
	}

	protected function preparedCode()
	{
		return Str::of($this->code)
			->prepend('return ')
			->append(';')
			->replace(
				'use Archetype\Facades\PHPFile;',
				''
			)
			->replace(
				'use Archetype\Facades\LaravelFile;',
				''
			)			
			->replace(
				'PHPFile',
				'\Archetype\Facades\PHPFile'
			)			
			->replace(
				'LaravelFile',
				'\Archetype\Facades\LaravelFile',				
			);
	}
}