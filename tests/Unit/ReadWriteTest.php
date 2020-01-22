<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\TestCase;

use LaravelFile;
use PHPFile;
use Storage;
use Config;

class ReadWriteTest extends TestCase
{
    /** @test
     * @group deletion
     */
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
    public function it_can_also_load_laravel_specific_files()
    {
        $file = LaravelFile::load('app/User.php');

        $this->assertInstanceOf(
            \PHPFileManipulator\LaravelFile::class, $file
        );
    }

    /** @test */
    public function it_has_attached_input_paths()
    {
        $file = LaravelFile::load('app/User.php');

        $this->assertEquals($file->inputPath(), base_path('app/User.php'));
        $this->assertEquals($file->inputName(), 'User.php');
    }    

    /** @wip-test
     * @group weird */
    public function it_can_write_to_various_location()
    {
        // debug
        LaravelFile::load('app/User.php')->debug();

        //$saved = LaravelFile::load(__DIR__ . '/../.debug/app/User.php');
        
        // $this->assertInstanceOf(
        //     \PHPFileManipulator\LaravelFile::class, $saved
        // );

        // // default save location is in .output when in development mode
        // LaravelFile::load('app/Console/Kernel.php')->save();
        // $saved = LaravelFile::load(__DIR__ . '/../.output/app/Console/Kernel.php');
        // $this->assertInstanceOf(
        //     \PHPFileManipulator\LaravelFile::class, $saved
        // );

        // // default save location
        // LaravelFile::setInputRoot(base_path('app/Http'))->load('Kernel.php')->save();
        // $saved = LaravelFile::setInputRoot(__DIR__ . '/../.output')->load('Kernel.php');
        // $this->assertInstanceOf(
        //     \PHPFileManipulator\LaravelFile::class, $saved
        // );
        
        // LaravelFile::load('app/User.php')
        //     ->save();
        // // Assert it is there        

        // LaravelFile::load('app/User.php')
        //     ->setOutputRoot('/full/path/to/output')
        //     ->save();
        // // Assert it is there

        // LaravelFile::load('app/User.php')
        //     ->setOutputRoot('relative/path/to/output')
        //     ->save();
        // // Assert it is there
    }
}
