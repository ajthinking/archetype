<?php

class FilePathTest extends Archetype\Tests\TestCase
{
    /** @test */
    public function a_file_has_an_input_path()
    {
        // relative
        $file = PHPFile::load('app/Models/User.php');
        $this->assertTrue(
            $file->inputDriver()->absolutePath() == base_path('app/Models/User.php')
        );

        // absolute
        $path = base_path('app/Models/User.php');
        $file = PHPFile::load($path);
        $this->assertTrue(
            $file->inputDriver()->absolutePath() == base_path('app/Models/User.php')
        );
    }
    
    /** @test */
    public function a_file_has_a_filename()
    {
        // relative
        $file = PHPFile::load('app/Models/User.php');
        $this->assertTrue(
            $file->inputDriver()->filename() == 'User'
        );

        // absolute
        $path = base_path('app/Models/User.php');
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
