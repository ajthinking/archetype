<?php

class ClassImplementsTest extends Archetype\Tests\TestCase
{
    /** @test */
    public function it_can_retrieve_class_implements()
    {
        $file = PHPFile::load('app/Models/User.php');

        $this->assertTrue(
            $file->implements() === []
        );
    }

    /** @test */
    public function it_can_set_class_implements()
    {
        $file = PHPFile::load('app/Models/User.php')->implements([
        "MyInterface"
        ]);

        $this->assertTrue(
            $file->implements() === [
                "MyInterface"
            ]
        );
    }

    /** @test */
    public function it_can_add_class_implements()
    {
        $file = PHPFile::load('app/Models/User.php')
            ->add()->implements(['MyFirstInterface'])
            ->add()->implements(['MySecondInterface']);

        $this->assertTrue(
            $file->implements() === [
                'MyFirstInterface',
                'MySecondInterface'
            ]
        );
    }
}
