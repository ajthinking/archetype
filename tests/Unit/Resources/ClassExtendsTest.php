<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit\Resources;

use Ajthinking\PHPFileManipulator\Tests\TestCase;

class ClassExtendsTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_class_extends()
    {
        $file = $this->userFile();

        $this->assertTrue(
            $file->classExtends() === 'Authenticatable'
        );
    }

    /** @test */
    public function it_can_set_class_implements()
    {
        $file = $this->userFile()->classExtends("My\BaseClass");

        $this->assertTrue(
            $file->classExtends() === "My\BaseClass"
        );
    } 
}