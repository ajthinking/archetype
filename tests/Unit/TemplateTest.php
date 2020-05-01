<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;
use BadMethodCallException;
use Illuminate\Support\Str;
use UnexpectedValueException;

use PHPFile;
use LaravelFile;

class TemplateTest extends FileTestCase
{
    /** @test */
    public function it_can_load_templates()
    {
        $templates = LaravelFile::templates();
        
        foreach($templates as $template) {
            $file = LaravelFile::make()->$template('Name');
            
            $this->assertTrue(
                // All template calls should return a LaravelFile
                get_class($file) === 'PHPFileManipulator\LaravelFile'
            );

            $this->assertTrue(
                // All files should have a php tag
                Str::contains($file->contents(), '<?php')
            );            
        }        
    }
    
    /** @test */
    public function it_can_not_load_unregistered_templates()
    {
        $this->expectException(BadMethodCallException::class);
        $file = LaravelFile::markdown('Haha');        
    }
    
    /** @test */
    public function it_can_save_templates_with_default_name()
    {
        $file = LaravelFile::make()->model('Explosion')->save();

        $this->assertTrue(
            is_file(__DIR__ . '/../.output/app/Explosion.php')
        );        
    }
    
    /** @test */
    public function it_can_use_intermidiate_make_when_creating_from_templates()
    {
        $file = LaravelFile::make()->model('Note')->save();
        $this->assertTrue(
            is_file(__DIR__ . '/../.output/app/Note.php')
        );        
    }

    /** @test */
    public function it_will_replace_namespace_and_class_name_when_creating_a_model()
    {
        $file = LaravelFile::make()->model('Guitar');

        $this->assertTrue(
            $file->namespace() == 'App'
        );

        $this->assertTrue(
            $file->className() == 'Guitar'
        );        
    }    
}
