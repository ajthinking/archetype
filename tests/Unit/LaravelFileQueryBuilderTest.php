<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;
use PHPFile;
use LaravelFile;
use PHPFileManipulator\Endpoints\PHP\FileQueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LaravelFileQueryBuilderTest extends FileTestCase
{    
    /** @test */
    public function it_can_scope_on_models()
    {        
        $this->assertCount(
            1, LaravelFile::models()->get()
        );
    }
    
    /** @test */
    public function it_can_scope_on_controllers()
    {        
        $this->assertCount(
            1, LaravelFile::controllers()->get()
        );
    }
    
    /** @test */
    public function it_can_get_user()
    {        
        $this->assertTrue(
            get_class(LaravelFile::user()) === 'PHPFileManipulator\LaravelFile'
        );
    }    
}