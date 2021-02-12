<?php

use Illuminate\Support\Facades\Config;

class ReadWriteTest extends Archetype\Tests\TestCase
{
    /** @test */
    public function it_wont_see_debug_or_output_folders_because_they_are_removed_at_start_up()
    {
        $this->assertFalse(
            is_dir(Config::get('archetype.roots.debug.root'))
        );

        $this->assertFalse(
            is_dir(Config::get('archetype.roots.output.root'))
        );
    }

    /** @test */
    public function it_can_load_php_files()
    {
        $file = PHPFile::load('public/index.php');

        $this->assertTrue(
            get_class($file) === 'Archetype\PHPFile'
        );
    }

    /** @test */
    public function it_can_load_files_with_absolute_path()
    {
        $file = PHPFile::load(
                base_path('vendor/ajthinking/archetype/src/snippets/relationships.php')
        );

        $this->assertTrue(
            get_class($file) === 'Archetype\PHPFile'
        );
    }

    /** @test */
    public function it_will_accept_forbidden_directories_when_explicitly_passed()
    {
        $file = PHPFile::in(
            'vendor/ajthinking/archetype/src/snippets'
        )->get()->first();

        $this->assertTrue(
            get_class($file) === 'Archetype\PHPFile'
        );
    }    

    /** @test */
    public function it_can_also_load_laravel_specific_files()
    {
        $file = LaravelFile::load('app/Models/User.php');

        $this->assertInstanceOf(
            \Archetype\LaravelFile::class, $file
        );
    } 

    /** @test */
    public function it_can_write_to_default_location()
    {        
        // default save location is in .output when in development mode
        LaravelFile::load('app/Models/User.php')->save();        
        
        $this->assertTrue(
            is_file(Config::get('archetype.roots.output.root') . '/app/Models/User.php')
        );

        // debug
        LaravelFile::load('app/Models/User.php')->debug();        

        $this->assertTrue(
            is_file(Config::get('archetype.roots.debug.root') . '/app/Models/User.php')
        );
    }   
}