<?php

namespace PHPFileManipulator\Tests\Unit\Endpoints;

use PHPFileManipulator\Tests\FileTestCase;

use LaravelFile;
use PHPFile;

class RelationshipsTest extends FileTestCase
{
    /** @test */
    public function it_can_insert_belongs_to_methods()
    {
        $file = LaravelFile::load('app/User.php');
        $file->addBelongsTo(['App\Department']);

        $this->assertContains(
            'department',
            $file->classMethodNames()
        );
    }

    /** @test */
    public function it_can_insert_belongs_to_many_methods()
    {
        $file = LaravelFile::load('app/User.php');
        $file->addBelongsToMany(['App\Visit']);

        $this->assertContains(
            'visits',
            $file->classMethodNames()
        );
    }
    
    /** @test */
    public function it_can_also_use_string_as_argument()
    {
        $file = LaravelFile::load('app/User.php');
        $file->addBelongsToMany('App\Visit');

        $this->assertContains(
            'visits',
            $file->classMethodNames()
        );
    }     
    
    /** @test */
    public function it_can_insert_has_many_methods()
    {
        $file = LaravelFile::load('app/User.php');
        $file->addHasMany(['App\Gun', 'App\Rose']);

        $this->assertContains(
            'guns',
            $file->classMethodNames()
        );

        $this->assertContains(
            'roses',
            $file->classMethodNames()
        );
    }
    
    /** @test */
    public function it_can_insert_has_one_methods()
    {
        $file = LaravelFile::load('app/User.php');
        $file->addHasOneMethods(['App\Phone']);

        $this->assertContains(
            'phone',
            $file->classMethodNames()
        );
    }    
}