<?php

namespace Archetype\Tests\Unit\Endpoints;

use Archetype\Tests\FileTestCase;

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