<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit;

use Ajthinking\PHPFileManipulator\Tests\TestCase;
use Ajthinking\PHPFileManipulator\PHPFile;
use BadMethodCallException;

class APIDelegationTest extends TestCase
{
    /** @test */
    public function it_can_delegate_method_calls()
    {
        $file = PHPFile::load(
            $this->samplePath('app/User.php')
        );

        // Existing method on $this
        $this->assertTrue(
            $file->ast() != null
        );

        // Existing method on resource
        $this->assertTrue(
            $file->namespace() != null
        );
        
        // Non existing method are catched
        $this->expectException(BadMethodCallException::class);
        $file->this_method_does_not_exists();
    }
}
