<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit\Resources;

use Ajthinking\PHPFileManipulator\Tests\TestCase;
use Ajthinking\PHPFileManipulator\LaravelFile;

class RelationshipsTest extends TestCase
{
    /** @test */
    public function it_can_insert_belongs_to_methods()
    {
        $file = $this->laravelUserFile();
        $file->addBelongsToMethods(['App\Department']);

        $this->assertContains(
            'department',
            $file->classMethodNames()
        );
    }

    /** @test */
    public function it_can_insert_belongs_to_many_methods()
    {
        $file = $this->laravelUserFile();
        $file->addBelongsToManyMethods(['App\Visit']);

        $this->assertContains(
            'visits',
            $file->classMethodNames()
        );
    }    
    
    /** @test */
    public function it_can_insert_has_many_methods()
    {
        $file = $this->laravelUserFile();
        $file->addHasManyMethods(['App\Gun', 'App\Rose']);

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
        $file = $this->laravelUserFile();
        $file->addHasOneMethods(['App\Phone']);

        $this->assertContains(
            'phone',
            $file->classMethodNames()
        );
    }    
}