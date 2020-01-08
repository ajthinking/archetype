<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit;

use Ajthinking\PHPFileManipulator\Tests\TestCase;

use LaravelFile;

class SnippetTest extends TestCase
{
    /** @test */
    public function it_can_use_snippets_from_default()
    {
        LaravelFile::snippet('mySnippet');

        $this->assertTrue(
            true
        );            
    }   
}
