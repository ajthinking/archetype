<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;

use LaravelFile;
use PHPFile;
use Storage;
use Config;

class ReadWriteTest extends FileTestCase
{
    /** @test */
    public function it_wont_see_debug_or_output_folders_because_they_are_removed_at_start_up()
    {
        $this->assertFalse(
            is_dir(__DIR__ . '/../.debug')
        );

        $this->assertFalse(
            is_dir(__DIR__ . '/../.output')
        );        
    }

    /** @test */
    public function it_can_load_php_files()
    {
        $file = PHPFile::load('public/index.php');

        $this->assertTrue(
            get_class($file) === 'PHPFileManipulator\PHPFile'
        );
    }

    /** @test */
    public function it_can_load_files_with_absolute_path()
    {
        $file = PHPFile::load(
                base_path('vendor/ajthinking/php-file-manipulator/src/snippets/relationships.php')
        );

        $this->assertTrue(
            get_class($file) === 'PHPFileManipulator\PHPFile'
        );
    }

    /** @test */
    public function it_will_accept_forbidden_directories_when_explicitly_passed()
    {
        $file = PHPFile::in(
            'vendor/ajthinking/php-file-manipulator/src/snippets'
        )->get()->first();

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

    /** @test */
    public function it_can_write_to_default_location()
    {        
        // default save location is in .output when in development mode
        LaravelFile::load('app/User.php')->save();        
        
        $this->assertTrue(
            is_file(__DIR__ . '/../.output/app/User.php')
        );

        // debug
        LaravelFile::load('app/User.php')->debug();        

        $this->assertTrue(
            is_file(__DIR__ . '/../.debug/app/User.php')
        );
    }   
}