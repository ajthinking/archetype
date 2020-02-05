<?php

namespace PHPFileManipulator\Tests\Unit\Endpoints;

use PHPFileManipulator\Tests\FileTestCase;

use PHPFile;
use LaravelFile;

class ClassImplementsTest extends FileTestCase
{
    /** @test */
    public function it_can_retrieve_class_implements()
    {
        $file = PHPFile::load('app/User.php');

        $this->assertTrue(
            $file->classImplements() === []
        );
    }

    /** @test */
    public function it_can_set_class_implements()
    {
        $file = PHPFile::load('app/User.php')->classImplements([
        "MyInterface" 
        ]);

        $this->assertTrue(
            $file->classImplements() === [
                "MyInterface" 
            ]
        );
    }

    /** @test */
    public function it_can_add_class_implements()
    {
        $file = PHPFile::load('app/User.php')
            ->addClassImplements(["MyFirstInterface" ])
            ->addClassImplements(["MySecondInterface" ]);

        $this->assertTrue(
            $file->classImplements() === [
                "MyFirstInterface",
                "MySecondInterface"
            ]
        );
    }    
}