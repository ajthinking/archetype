<?php

namespace PHPFileManipulator\Tests\Unit\Endpoints;

use PHPFileManipulator\Tests\FileTestCase;

use PHPFile;
use LaravelFile;

class ClassNameTest extends FileTestCase
{
    /** @test */
    public function it_can_retrieve_class_name()
    {
        $file = PHPFile::load('app/User.php');

        $this->assertTrue(
            $file->className() === "User"
        );
    }
    
    /** @test */
    public function it_can_set_class_name()
    {
        // on a file with a class
        $this->assertTrue(
            PHPFile::load('app/User.php')->className("NewName")->className() === "NewName"
        );

        // on a file without a class
        $this->assertTrue(
            PHPFile::load('public/index.php')->className("NewName")->className() === null
        );        
    }
}