<?php

class NamespaceTest extends Archetype\Tests\FileTestCase
{
    /** @test */
    public function it_can_retrieve_namespace()
    {
        // on a file with namespace
        $this->assertTrue(
            PHPFile::load('app/Models/User.php')->namespace() === 'App\Models'
        );

        // on a file without namespace
        $this->assertTrue(
            PHPFile::load('public/index.php')->namespace() === null
        );
    }

    /** @test */
    public function it_can_set_namespace()
    {
        // on a file with namespace
        $this->assertTrue(
            PHPFile::load('app/Models/User.php')->namespace('New\Namespace')->namespace() === 'New\Namespace'
        );

        // on a file without namespace
        $this->assertTrue(
            PHPFile::load('public/index.php')->namespace('New\Namespace')->namespace() === 'New\Namespace'
        );        
    }
    
    /** @test */
    public function it_can_remove_namespace()
    {
        // on a file with namespace
        $this->assertTrue(
            PHPFile::load('app/Models/User.php')->remove()->namespace()->namespace() === null
        );

        // on a file without namespace
        $this->assertTrue(
            PHPFile::load('public/index.php')->remove()->namespace()->namespace() === null
        );        
    }        
}
