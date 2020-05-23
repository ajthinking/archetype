<?php

namespace PHPFileManipulator\Tests\Unit\Endpoints\PHP;

use PHPFileManipulator\Tests\FileTestCase;
use BadMethodCallException;
use Illuminate\Support\Str;
use UnexpectedValueException;

use PHPFile;

class MakerTest extends FileTestCase
{
    /** @test */
    public function the_php_file_maker_defaults_to_root()
    {
        $output = PHPFile::make()->file('script.php')->outputDriver();
        $this->assertEquals('', $output->relativeDir);
        $this->assertEquals('script', $output->filename);
        $this->assertEquals('php', $output->extension);
    }

    /** @test */
    public function the_php_file_maker_can_write_into_directories()
    {
        $output = PHPFile::make()->file('app/HTTP/script.php')->outputDriver();
        $this->assertEquals('app/HTTP', $output->relativeDir);
        $this->assertEquals('script', $output->filename);
        $this->assertEquals('php', $output->extension);
    }
    
    /** @test */
    public function the_php_class_maker_accepts_a_namespaced_class()
    {
        $file = PHPFile::make()->class('Weapons\RocketLauncher');
        
        $output = $file->outputDriver();
        $this->assertEquals('app/Weapons', $output->relativeDir);
        $this->assertEquals('RocketLauncher', $output->filename);
        $this->assertEquals('php', $output->extension);

        $this->assertEquals('App\Weapons', $file->namespace());
        $this->assertEquals('RocketLauncher', $file->className());
    }
}
