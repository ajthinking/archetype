<?php

class ClassExtendsTest extends Archetype\Tests\FileTestCase
{
    /** @test */
    public function it_can_retrieve_class_extends()
    {
        $file = PHPFile::load('app/Models/User.php');

        $this->assertTrue(
            $file->extends() === 'Authenticatable'
        );
    }

    /** @test */
    public function it_can_set_class_extends()
    {
        $file = PHPFile::load('app/Models/User.php')->extends("My\BaseClass");

        $this->assertTrue(
            $file->extends() === "My\BaseClass"
        );
    }
    

    /** @test
     * @group only
    */
    public function it_can_set_class_extends_when_its_empty()
    {
        $file = PHPFile::load('app/Http/Middleware/RedirectIfAuthenticated.php')->extends("My\BaseClass");

        $this->assertTrue(
            $file->extends() === "My\BaseClass"
        );
    }         
}