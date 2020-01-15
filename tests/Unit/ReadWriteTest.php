<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\TestCase;

use LaravelFile;
use PHPFile;

class PHPFileTest extends TestCase
{
    /** @test */
    public function it_can_load_php_files()
    {
        $file = PHPFile::load('public/index.php');

        $this->assertTrue(
            get_class($file) === 'PHPFileManipulator\PHPFile'
        );
    }

    /** @test */
    public function it_can_also_load_laravel_specific_files()
    {
        $file = LaravelFile::load('app/User.php');

        $this->assertInstanceOf(
            \PHPFileManipulator\LaravelFile::class, $file
        );
    }  
}
