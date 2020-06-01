<?php

class ClassExtendsTest extends Archetype\Tests\FileTestCase
{
    /** @test */
    public function it_can_retrieve_class_extends()
    {
        $file = PHPFile::load('app/User.php');

        $this->assertTrue(
            $file->extends() === 'Authenticatable'
        );
    }

    /** @test */
    public function it_can_set_class_implements()
    {
        $file = PHPFile::load('app/User.php')->extends("My\BaseClass");

        $this->assertTrue(
            $file->extends() === "My\BaseClass"
        );
    } 
}