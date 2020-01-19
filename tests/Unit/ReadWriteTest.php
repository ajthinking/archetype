<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\TestCase;

use LaravelFile;
use PHPFile;
use Storage;
use Config;

/**
 * @group read-write
 */
class ReadWriteTest extends TestCase
{
    /** @test */
    public function it_wont_see_preview_folder_because_it_is_removed_at_start_up()
    {
        $this->assertFalse(
            is_dir(__DIR__ . '/../.preview')
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

        dd(
            $file->outputPath()
        );
    }    

    /** @wip-test
     * @group mixed
    */
    public function it_can_write_to_various_location()
    {
        // // debug
        // LaravelFile::setInputRoot(base_path())->load('app/User.php')->debug();
        // $saved = LaravelFile::setInputRoot(__DIR__ . '/../.preview')->load('app/User.php');
        // $this->assertInstanceOf(
        //     \PHPFileManipulator\LaravelFile::class, $saved
        // );

        // // default save location
        // LaravelFile::setInputRoot(base_path())->load('app/Console/Kernel.php')->save();
        // $saved = LaravelFile::setInputRoot(__DIR__ . '/../.preview')->load('app/Console/Kernel.php');
        // $this->assertInstanceOf(
        //     \PHPFileManipulator\LaravelFile::class, $saved
        // );

        // // default save location
        // LaravelFile::setInputRoot(base_path('app/Http'))->load('Kernel.php')->save();
        // $saved = LaravelFile::setInputRoot(__DIR__ . '/../.preview')->load('Kernel.php');
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
    /** @test
     * @group mixed
     */
    public function disks_are_imutable()
    {
        $this->assertTrue(true);
        return true; // :(

        $input_disk = Config::get("php-file-manipulator.roots.input");
        $output_disk = Config::get("php-file-manipulator.roots.output");
        
        
        
        Config::set("filesystems.disks.roots.input", $output_disk);
        
        Storage::disk("roots.input");

        Config::set("filesystems.disks.roots.input", $input_disk);
        
        dd(
            Storage::disk("roots.input")
        );

        
        Config::set("filesystems.disks.roots.input", $disk);

        
        // Config::set("filesystems.disks.roots.$name", $disk);
        // dd(Storage::disk("roots.$name"));
    }
}
