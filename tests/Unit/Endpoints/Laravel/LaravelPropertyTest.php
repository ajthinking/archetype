<?php

namespace PHPFileManipulator\Tests\Unit\Endpoints;

use PHPFileManipulator\Tests\FileTestCase;

use PHPFile;
use LaravelFile;

class LaravelPropertyTest extends FileTestCase
{
    /** @test */
    public function it_can_retrieve_fillables()
    {
        $this->assertTrue(
            LaravelFile::load('app/User.php')->fillable() == ['name', 'email', 'password',]
        );
    }

    /** @test */
    public function it_can_retrieve_hidden()
    {
        $this->assertTrue(
            LaravelFile::load('app/User.php')->hidden() == ['password', 'remember_token',]
        );
    }
    
    /** @test */
    public function it_wont_break_if_properties_are_missing()
    {
        $this->assertNull(
            LaravelFile::load('public/index.php')->hidden()
        );
    }

    /** @test */
    public function it_will_assume_array_if_we_are_inserting_on_a_new_hidden_property()
    {
        $hidden = LaravelFile::load('app/User.php')
            ->remove()->hidden()
            ->hidden('ghost')->hidden();

        $this->assertEquals(
            ['ghost'],
            $hidden            
        );

        $hidden = LaravelFile::load('app/User.php')
            ->remove()->hidden()
            ->hidden(['ghost'])->hidden();

        $this->assertEquals(
            ['ghost'],
            $hidden            
        );
    }
    
    /** @test */
    public function it_can_set_fillables()
    {
        $this->assertEquals(
            LaravelFile::load('app/User.php')->fillable(['guns', 'roses'])->fillable(),
            ['guns', 'roses',]
        );
    }

    /** @test */
    public function it_can_add_fillables()
    {
        $this->assertEquals(
            LaravelFile::load('app/User.php')
                ->fillable(['guns', 'roses'])
                ->add()->fillable(['metallica'])
                ->fillable(),
            ['guns', 'roses', 'metallica']
        );
    }    
    
    /** @test */
    public function it_can_set_hidden()
    {
        $this->assertEquals(
            LaravelFile::load('app/User.php')->hidden(['metallica', 'ozzy'])->hidden(),
            ['metallica', 'ozzy',]
        );
    }    
}