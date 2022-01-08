<?php

namespace Archetype\Tests\Support;

use function PHPUnit\Framework\assertEquals;
use Illuminate\Support\Str;

class TestableReadme
{
	public string $content;

	public array $examples = [];

	public function __construct()
	{
		$this->contents = file_get_contents(__DIR__.'/../../readme.md');
		$this->parseExamples();
	}

	public static function make()
	{
		return new static;
	}

	protected function parseExamples()
	{
		$lines = Str::of($this->contents)->explode(PHP_EOL);
		$cursor = 0;

		while($cursor < $lines->count()) {
			$line = $lines[$cursor];

			$codeStartLine = $this->isExampleStart($line) ? $cursor : null;
			
			if(!$codeStartLine) {
				$cursor++;
				continue;
			}

			$cursor = $codeStartLine + 1;
			$codeEndLine = $lines->slice($cursor)->search('```');

			$cursor = $codeEndLine + 1;
			$outputStartLine = $lines->slice($cursor)->search('```');

			$cursor = $outputStartLine + 1;
			$outputEndLine = $lines->slice($cursor)->search('```');

			$cursor = $outputEndLine + 1;
			
			$code = $lines->slice($codeStartLine+1, $codeEndLine - $codeStartLine-1)->implode(PHP_EOL);
			$output = $lines->slice($outputStartLine+1, $outputEndLine - $outputStartLine-1)->implode(PHP_EOL);

			array_push(
				$this->examples,
				new ReadmeExample($code, $output)
			);
		}
	}

	protected function isExampleStart(string $line)
	{
		return preg_match("/^```php example/", $line);
	}
}