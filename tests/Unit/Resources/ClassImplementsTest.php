<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit\Resources;

use Ajthinking\PHPFileManipulator\Tests\TestCase;

class ClassImplementsTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_class_implements()
    {
        $file = $this->userFile();

        $this->assertTrue(
            $file->classImplements() === []
        );
    }

    /** @test */
    public function it_can_set_class_implements()
    {
        $file = $this->userFile()->classImplements([
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
        $file = $this->userFile()
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