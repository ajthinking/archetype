<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit\Resources;

use Ajthinking\PHPFileManipulator\Tests\TestCase;

class ClassPropertyTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_fillables()
    {
        $this->assertTrue(
            $this->laravelUserFile()->fillable() == ['name', 'email', 'password',]
        );
    }

    /** @test */
    public function it_can_retrieve_hidden()
    {
        $this->assertTrue(
            $this->laravelUserFile()->hidden() == ['password', 'remember_token',]
        );
    }
    
    /** @test */
    public function it_wont_break_if_properties_are_missing()
    {
        $this->assertNull(
            $this->routesFile()->hidden()
        );
    }
    
    /** @test */
    public function it_can_set_fillables()
    {
        $this->assertEquals(
            $this->laravelUserFile()->fillable(['guns', 'roses'])->fillable(),
            ['guns', 'roses',]
        );
    }
    
    /** @test */
    public function it_can_set_hidden()
    {
        $this->assertEquals(
            $this->laravelUserFile()->hidden(['metallica', 'ozzy'])->hidden(),
            ['metallica', 'ozzy',]
        );
    }    
}