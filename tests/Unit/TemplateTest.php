<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit;

use Ajthinking\PHPFileManipulator\Tests\TestCase;
use BadMethodCallException;

use LaravelFile;

class TemplateTest extends TestCase
{
    /** @test */
    public function it_can_load_templates()
    {
        $file = LaravelFile::model('Bar');
        $this->assertTrue(
            get_class($file) === 'Ajthinking\PHPFileManipulator\LaravelFile'
        );

        $file = LaravelFile::model('Drink')->className('Oboy');
        $this->assertTrue(
            get_class($file) === 'Ajthinking\PHPFileManipulator\LaravelFile'
        );        
    }
    
    /** @test */
    public function it_can_not_load_unregistered_templates()
    {
        $this->expectException(BadMethodCallException::class);
        $file = LaravelFile::some_non_existing_template('Haha');        
    }    
}
