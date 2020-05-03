<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;
use PHPFile;
use LaravelFile;
use PHPFileManipulator\Endpoints\PHP\FileQueryBuilder;
use PHPFileManipulator\Endpoints\Laravel\LaravelFileQueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class IntermediateDirectivesTest extends FileTestCase
{
    /** @test */
    public function it_will_remember_directives_when_chained()
    {
        $file = PHPFile::load('app/User.php')->add()->remove();

        $this->assertEquals(
            ['add' => true, 'remove' => true],
            $file->directives(),
        );
    }
    
    /** @test */
    public function it_will_forget_directives_on_continue()
    {
        $file = PHPFile::load('app/User.php')->add()->remove()->continue();
        $this->assertEmpty(
            $file->directives()
        );
    }    
}