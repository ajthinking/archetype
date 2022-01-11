<?php

use Archetype\Tests\Support\TestableMarkdown;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;

const EMPTY_ = <<< "MARKDOWN"
MARKDOWN;

const NO_EXAMPLE = <<< 'MARKDOWN'
### Test this pls
```php
$code = 123;
```
MARKDOWN;

const ONE_ASSERT_CAN_RUN = <<< 'MARKDOWN'
### Test this pls
```php example assertCanRun
$code = 123;
```
MARKDOWN;

const MAKE_AN_EMPTY_FILE = <<< 'MARKDOWN'
### Make an empty file
```php example
PHPFile::make()->file(\\App\\Dummy::class)
	->render()
```

```php
<?php
```
MARKDOWN;

test('it can parse empty file', function() {
	$examples = (new TestableMarkdown(EMPTY_))->examples;

	assertEmpty($examples);
});

test('it ignores section if first code block is not an example', function() {
	$examples = (new TestableMarkdown(NO_EXAMPLE))->examples;

	assertEmpty($examples);
});

test('it can parse a file with a single code', function() {
	$examples = (new TestableMarkdown(ONE_ASSERT_CAN_RUN))->examples;

	assertCount(1, $examples);

	$example = $examples[0];

	assertEquals('### Test this pls', $example->heading());
	assertEquals('$code = 123;', $example->code());
});

test('it can make an empty file test', function() {
	$examples = TestableMarkdown::make(__DIR__.'/../../docs.md')->examples;
	$example = $examples[0];
});