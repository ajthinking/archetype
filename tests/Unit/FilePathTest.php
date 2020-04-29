<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;

use LaravelFile;
use PHPFile;
use Str;
use TypeError;

class FilePathTest extends FileTestCase
{
    /** @test */
    public function a_file_has_an_input_path()
    {
        // relative
        $file = PHPFile::load('app/User.php');
        $this->assertTrue(
            $file->inputDriver()->absolutePath() == base_path('app/User.php')
        );

        // absolute
        $path = base_path('app/User.php');
        $file = PHPFile::load($path);
        $this->assertTrue(
            $file->inputDriver()->absolutePath() == base_path('app/User.php')
        );        
    }    
    
    /** @test */
    public function a_file_has_a_filename()
    {
        // relative
        $file = PHPFile::load('app/User.php');
        $this->assertTrue(
            $file->inputDriver()->filename() == 'User'
        );

        // absolute
        $path = base_path('app/User.php');
        $file = PHPFile::load($path);
        $this->assertTrue(
            $file->inputDriver()->filename() == 'User'
        );        
    }
    
    public function files_created_with_fromString_must_be_explicitly_named()
    {
        $file = PHPFile::fromString('<?php');

        $this->expectException(TypeError::class);

        $file->save(); // It dont know where to save!
    }    
}
