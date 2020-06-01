<?php

class RelationshipsTest extends Archetype\Tests\FileTestCase
{
    /** @test */
    public function it_can_insert_belongs_to_methods()
    {
        $file = LaravelFile::load('app/User.php');
        $file->belongsTo(['App\Department']);

        $this->assertContains(
            'department',
            $file->methodNames()
        );
    }

    /** @test */
    public function it_can_insert_belongs_to_many_methods()
    {
        $file = LaravelFile::load('app/User.php');
        $file->belongsToMany(['App\Visit']);

        $this->assertContains(
            'visits',
            $file->methodNames()
        );
    }
    
    /** @test */
    public function it_can_also_use_string_as_argument()
    {
        $file = LaravelFile::load('app/User.php');
        $file->belongsToMany('App\Visit');

        $this->assertContains(
            'visits',
            $file->methodNames()
        );
    }     
    
    /** @test */
    public function it_can_insert_has_many_methods()
    {
        $file = LaravelFile::load('app/User.php');
        $file->hasMany(['App\Gun', 'App\Rose']);

        $this->assertContains(
            'guns',
            $file->methodNames()
        );

        $this->assertContains(
            'roses',
            $file->methodNames()
        );
    }
    
    /** @test */
    public function it_can_insert_has_one_methods()
    {
        $file = LaravelFile::load('app/User.php');
        $file->hasOne(['App\Phone']);

        $this->assertContains(
            'phone',
            $file->methodNames()
        );
    }

    /** @test */
    public function it_will_overwrite_already_existing_method()
    {
        $file = LaravelFile::load('app/User.php')
            ->hasOne(['App\Phone'])
            ->hasOne(['App\Phone']);

        $this->assertCount(
            1,
            $file->methodNames()
        );
    }    
}