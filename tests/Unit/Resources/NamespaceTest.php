<?php

namespace PHPFileManipulator\Tests\Unit\Endpoints;

use PHPFileManipulator\Tests\FileTestCase;

use PHPFile;
use LaravelFile;

class NamespaceTest extends FileTestCase
{
    /** @test */
    public function it_can_retrieve_namespace()
    {
        // on a file with namespace
        $this->assertTrue(
            PHPFile::load('app/User.php')->namespace() === 'App'
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
            PHPFile::load('app/User.php')->namespace('New\Namespace')->namespace() === 'New\Namespace'
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
            PHPFile::load('app/User.php')->removeNamespace()->namespace() === null
        );

        // on a file without namespace
        $this->assertTrue(
            PHPFile::load('public/index.php')->removeNamespace()->namespace() === null
        );        
    }        
}
