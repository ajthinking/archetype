<?php

namespace PHPFileManipulator\Tests\Unit\Endpoints\PHP;

use PHPFileManipulator\Tests\FileTestCase;
use BadMethodCallException;
use Illuminate\Support\Str;
use UnexpectedValueException;

use PHPFile;

class TemplateTest extends FileTestCase
{
    /** @test */
    public function it_can_make_files_with_basic_php_templates()
    {
        // $this->assertInstanceOf(
        //     \PHPFileManipulator\PHPFile::class,
        //     PHPFile::make()->file('cool.php')
        // );

        $this->assertInstanceOf(
            \PHPFileManipulator\PHPFile::class,
            PHPFile::make()->class('CoolClass')
        );
    }
}
