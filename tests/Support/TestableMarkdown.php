<?php

namespace Archetype\Tests\Support;

use Illuminate\Support\Str;

class TestableMarkdown
{
	public string $contents;

	public array $examples = [];

	final public function __construct(string $contents)
	{
		$this->contents = $contents;
		$this->parseExamples();
	}

	public static function make($path)
	{
		return new static(
			file_get_contents($path)
		);
	}

	public function toPestTestArray()
	{
		return collect($this->examples)->map(function($example) {
			return [$example->config['heading'], $example];
		})->toArray();
	}	

	protected function parseExamples()
	{
		$lines = Str::of($this->contents)->explode(PHP_EOL);
		$cursor = 0;

		while($cursor < $lines->count()) {
			$line = $lines[$cursor];

			if($this->isHeading($line)) {
				$sectionLines = $this->getSectionLines($lines, $cursor);
				$this->registerExample($sectionLines->values());
				$cursor += $sectionLines->count();
			} else {
				$cursor++;
				continue;
			}
		}
	}

	protected function isExampleStart(string $line)
	{
		return preg_match("/^```php example.*/", $line);
	}

	protected function isHeading(string $line)
	{
		return preg_match("/^[#]+ .*/", $line);
	}

	protected function getDirectives(string $line)
	{
		preg_match("/^```php example (.*)/", $line, $matches);

		if(!$matches) return ['assertCodeReturnsOutput'];

		return [$matches[1]];
	}

	protected function getSectionLines($lines, $startLineIndex)
	{
		$endLineIndex = $lines->slice($startLineIndex+1)->search(function($line) {
			return $this->isHeading($line);
		});

		return $lines->slice($startLineIndex)->take(
			$endLineIndex ? $endLineIndex - $startLineIndex : 10000000
		);
	}

	protected function registerExample($lines)
	{
		$config = [
			'heading' => $lines->first(),
		];

		$codeTags = $lines->filter(function($line) {
			return preg_match("/^```/", $line);
		})->keys();

		if($codeTags->isEmpty()) return;
		
		if(!$this->isExampleStart($lines[$codeTags[0]])) return;

		$codeStartLine = $codeTags[0];
		$codeEndLine = $codeTags[1];

		$code = $lines->slice($codeStartLine+1, $codeEndLine - $codeStartLine-1)->implode(PHP_EOL);
		$config['code'] = $code;

		if($codeTags->count() > 2) {
			$outputStartLine = $codeTags[2];
			$outputEndLine = $codeTags[3];			
			$output = $lines->slice($outputStartLine+1, $outputEndLine - $outputStartLine-1)->implode(PHP_EOL);
			$config['output'] = $output;
		}

		array_push(
			$this->examples,
			new ReadmeExample($config)			
		);	
	}
}