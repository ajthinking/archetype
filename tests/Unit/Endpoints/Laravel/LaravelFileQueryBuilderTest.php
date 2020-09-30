<?php

use Archetype\Endpoints\PHP\PHPFileQueryBuilder;
use Archetype\

class LaravelFileQueryBuilderTest extends Archetype\Tests\FileTestCase
{    
    /** @test */
    public function it_can_scope_on_models()
    {        
        $this->assertCount(
            1, LaravelFile::models()->get()
        );
    }
    
    /** @test */
    public function it_can_scope_on_controllers()
    {        
        $this->assertCount(
            1, LaravelFile::controllers()->get()
        );
    }

    // /** @test */ THIS BREAKS ON GITHUB ACTIONS (DUE TO COMPOSER CREATE-PROJECT (?))
    // public function it_can_scope_on_migrations()
    // {        
    //     $this->assertCount(
    //         3, LaravelFile::migrations()->get()
    //     );
    // }    
    
    /** @test */
    public function it_can_get_user()
    {        
        $this->assertTrue(
            get_class(LaravelFile::load('app/Models/User.php')) === 'Archetype\LaravelFile'
        );
    }    
}