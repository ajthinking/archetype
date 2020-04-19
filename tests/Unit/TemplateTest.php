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
            $file = LaravelFile::$template('Name');
            
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
    
    // /** @test */
    // public function it_can_not_rely_on_default_path_for_templates_yet()
    // {
    //     //$this->expectException(UnexpectedValueException::class);
    //     $file = LaravelFile::model('Explosion')->save(
    //         /* for now path is mandatory when using templates */
    //     );
    // }    
}
