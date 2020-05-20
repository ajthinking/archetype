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
        $this->assertEquals('script.php', $output->filename);
    }

    /** @test */
    public function the_php_file_maker_can_write_into_directories()
    {
        $this->markTestIncomplete();
        $output = PHPFile::make()->file('app/HTTP/script.php')->outputDriver();
        $this->assertEquals('app/HTTP', $output->relativeDir);
        $this->assertEquals('script.php', $output->filename);
    }  
}
