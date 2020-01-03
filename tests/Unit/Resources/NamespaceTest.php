<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit\Resources;

use Ajthinking\PHPFileManipulator\Tests\TestCase;

class NamespaceTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_namespace()
    {
        // on a file with namespace
        $this->assertTrue(
            $this->userFile()->namespace() === 'App'
        );

        // on a file without namespace
        $this->assertTrue(
            $this->routesFile()->namespace() === null
        );
    }

    /** @test */
    public function it_can_set_namespace()
    {
        // on a file with namespace
        $this->assertTrue(
            $this->userFile()->namespace('New\Namespace')->namespace() === 'New\Namespace'
        );

        // on a file without namespace
        $this->assertTrue(
            $this->routesFile()->namespace('New\Namespace')->namespace() === 'New\Namespace'
        );        
    }
    
    /** @test */
    public function it_can_remove_namespace()
    {
        // on a file with namespace
        $this->assertTrue(
            $this->userFile()->removeNamespace()->namespace() === null
        );

        // on a file without namespace
        $this->assertTrue(
            $this->routesFile()->removeNamespace()->namespace() === null
        );        
    }        
}
