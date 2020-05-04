<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;
use PhpParser\Node\Stmt\ClassMethod;

use PHPFile;
use LaravelFile;

use PHPFileManipulator\Support\Snippet;

class SnippetTest extends FileTestCase
{
    /** @test */
    public function it_can_load_class_methods_from_snippet_defaults()
    {
        $this->assertInstanceOf(
            ClassMethod::class,
            Snippet::___HAS_MANY_METHOD___()
        );            
    }

    /** @test */
    public function it_can_replace_snippet_names()
    {
        $method = Snippet::___HAS_MANY_METHOD___([
            '___HAS_MANY_METHOD___' => 'guitars'
        ]);

        $this->assertEquals(
            LaravelFile::load('app/User.php')->addClassMethod([$method])->classMethodNames(),
            ['guitars']
        );            
    }    
    
    /** @test */
    public function it_cant_load_non_existing_snippets_from_defaults()
    {
        $this->assertNull(
            Snippet::NoSUchSnippet()
        );            
    }   
}
