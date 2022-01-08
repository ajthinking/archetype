<?php

use function PHPUnit\Framework\assertTrue;

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;
use Archetype\Tests\Support\TestableReadme as Readme;

it('is cool', function() {
	foreach(Readme::make()->examples as $example) {
		$example->assertCodeReturnsOutput();
	}
});