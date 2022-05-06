<?php

namespace Archetype\Tests\Support;

use Exception;
use Illuminate\Support\Str;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ReadmeExample
{
	public array $config = [
		'code' => '',
		'output' => '',
		'directives' => ['assertCodeReturnsOutput']
	];

	public function __construct(array $config)
	{
		$this->config = array_merge($this->config, $config);
	}

	public function code()
	{
		return $this->config['code'];
	}

	public function heading()
	{
		return $this->config['heading'];
	}	

	public function output()
	{
		return $this->config['output'] ?? null;
	}	

	public function assertCodeReturnsOutput()
	{
		$expected = $this->output();
		$actual = eval($this->preparedCode());
		$message = "Expected: $expected".PHP_EOL.PHP_EOL."Got: $actual".PHP_EOL.PHP_EOL;

		assertEquals($expected, $actual, $message);

		return $this;
	}

	public function assertCanRun()
	{
		try {
			eval($this->preparedCode());
			assertTrue(true);
		} catch(Exception $e) {
			assertTrue(false, 'Could not eval code!');
		}

		return $this;
	}

	public function assertValid()
	{
		return $this->output()
			? $this->assertCodeReturnsOutput()
			: $this->assertCanRun();
	}

	protected function preparedCode()
	{
		return Str::of($this->code())
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