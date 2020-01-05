<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit;

use Ajthinking\PHPFileManipulator\Tests\TestCase;
use BadMethodCallException;
use Illuminate\Support\Str;

use LaravelFile;

class TemplateTest extends TestCase
{
    /** @test */
    public function it_can_load_templates()
    {
        $templates = LaravelFile::templates();
        
        foreach($templates as $template) {
            $file = LaravelFile::$template('Name');
            
            $this->assertTrue(
                // All template calls should return a LaravelFile
                get_class($file) === 'Ajthinking\PHPFileManipulator\LaravelFile'
            );

            $this->assertTrue(
                // All files should have a php tag
                Str::contains($file->contents, '<?php')
            );            
        }        
    }
    
    /** @test */
    public function it_can_not_load_unregistered_templates()
    {
        $this->expectException(BadMethodCallException::class);
        $file = LaravelFile::some_non_existing_template('Haha');        
    }    
}
