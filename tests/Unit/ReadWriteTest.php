<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\TestCase;

use LaravelFile;
use PHPFile;

/**
 * @group read-write
 */
class ReadWriteTest extends TestCase
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

    /** @wip-test */
    public function it_can_write_to_a_debug_location()
    {
        LaravelFile::load('app/User.php')
            ->debug();
        // See it in debug

        LaravelFile::load('app/User.php')
            ->debug('/full/path/to/custom/debug');
        // See it in custom debug

        LaravelFile::load('app/User.php')
            ->debug('relative/path/to/debug');
        // See it in X ?

        LaravelFile::setInputRoot('app')
            ->load('User.php');
        // Assert insance
        
        LaravelFile::load('app/User.php')
            ->save();
        // Assert it is there        

        LaravelFile::load('app/User.php')
            ->setOutputRoot('/full/path/to/output')
            ->save();
        // Assert it is there

        LaravelFile::load('app/User.php')
            ->setOutputRoot('relative/path/to/output')
            ->save();
        // Assert it is there
    }    
}
