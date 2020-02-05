<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;

use LaravelFile;
use PHPFile;
use Str;
use UnexpectedValueException;

class FilePathTest extends FileTestCase
{
    /** @test */
    public function a_file_has_an_input_path()
    {
        // relative
        $file = PHPFile::load('app/User.php');
        $this->assertTrue(
            $file->inputPath() == base_path('app/User.php')
        );

        // absolute
        $path = base_path('app/User.php');
        $file = PHPFile::load($path);
        $this->assertTrue(
            $file->inputPath() == base_path('app/User.php')
        );        
    }

    /** @test */
    public function a_file_has_an_input_dir()
    {
        // relative
        $file = PHPFile::load('app/User.php');
        $this->assertTrue(
            $file->inputDir() == app_path()
        );

        // absolute
        $path = base_path('app/User.php');
        $file = PHPFile::load($path);
        $this->assertTrue(
            $file->inputDir() == app_path()
        );        
    }    
    
    /** @test */
    public function a_file_has_an_input_name()
    {
        // relative
        $file = PHPFile::load('app/User.php');
        $this->assertTrue(
            $file->inputName() == 'User.php'
        );

        // absolute
        $path = base_path('app/User.php');
        $file = PHPFile::load($path);
        $this->assertTrue(
            $file->inputName() == 'User.php'
        );        
    }
    
    /** @test */
    public function a_file_has_an_output_path()
    {
        // relative
        $file = PHPFile::load('app/User.php');

        $this->assertTrue(
            Str::contains($file->outputPath(), '/.output/app/User.php')
        );

        // absolute
        $path = base_path('app/User.php');
        $file = PHPFile::load($path);
        $this->assertTrue(
            Str::contains($file->outputPath(), '/.output/app/User.php')
        );        
    }
    
    /** @test */
    public function files_created_with_fromString_must_be_explicitly_named()
    {
        $file = PHPFile::fromString('<?php');
        
        $this->expectException(UnexpectedValueException::class);

        $file->save(); // It dont know where to save!
    }    
}
