<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;
use BadMethodCallException;
use Illuminate\Support\Str;
use UnexpectedValueException;
use PHPFileManipulator\Support\AST\Unpacker;
use PHPParser\BuilderHelpers;

use PHPFile;
use LaravelFile;

class UnpackerTest extends FileTestCase
{
    /** @test */
    public function it_can_unpack_a_string()
    {
        $ast = BuilderHelpers::normalizeValue('single');
        
        $this->assertEquals(
            Unpacker::unpack($ast),
            'single'          
        );
    }

    /** @test */
    public function it_can_unpack_an_array_of_strings()
    {
        $ast = BuilderHelpers::normalizeValue(['a', 'b', 'c']);
        
        $this->assertEquals(
            Unpacker::unpack($ast),
            ['a', 'b', 'c']          
        );
    }
}
