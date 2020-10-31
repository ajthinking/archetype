<?php

class ClassConstantTest extends Archetype\Tests\FileTestCase
{
    /** @test */
    public function it_can_get_a_class_constant()
    {
        $this->assertEquals(
            PHPFile::load('app/Providers/RouteServiceProvider.php')->classConstant('HOME'),
            '/home'            
        );
    }

    /** @test */
    public function it_can_update_existing_class_constants()
    {
        $this->assertEquals(
            PHPFile::load('app/Providers/RouteServiceProvider.php')
                ->classConstant('HOME', '/new_home')
                ->classConstant('HOME'),
            '/new_home'            
        );
    }

    /** @test */
    public function it_can_create_a_new_class_constant()
    {
        $constant = PHPFile::load('app/Models/User.php')
            ->classConstant('BRAND_NEW', 'it will work')
            ->classConstant('BRAND_NEW');

        $this->assertEquals(
            $constant,
            'it will work'
        );
    }
}