<?php

namespace PHPFileManipulator\Tests\Unit\Endpoints;

use PHPFileManipulator\Tests\FileTestCase;

use PHPFile;
use LaravelFile;

class TraitTest extends FileTestCase
{
    /** @test */
    public function it_can_test()
    {
        $this->assertEquals(
            PHPFile::load('app/User.php')->trait(),
            ['Notifiable']
        );

    }
}