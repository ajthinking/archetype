<?php

class RelationshipsTest extends Archetype\Tests\TestCase
{
    /** @test */
    public function it_can_insert_belongs_to_methods()
    {
        $file = LaravelFile::load('app/Models/User.php');
        $file->belongsTo(['App\Department']);

        $this->assertContains(
            'department',
            $file->methodNames()
        );
    }

    /** @test */
    public function it_can_insert_belongs_to_many_methods()
    {
        $file = LaravelFile::load('app/Models/User.php');
        $file->belongsToMany(['App\Visit', 'App\\Purchase']);

        $this->assertContains(
            'visits',
            $file->methodNames()
        );
    }
    
    /** @test */
    public function it_can_also_use_string_as_argument()
    {
        $file = LaravelFile::load('app/Models/User.php');
        $file->belongsToMany('App\Visit');

        $this->assertContains(
            'visits',
            $file->methodNames()
        );
    }     
    
    /** @test */
    public function it_can_insert_has_many_methods()
    {
        $file = LaravelFile::load('app/Models/User.php');
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
        $file = LaravelFile::load('app/Models/User.php');
        $file->hasOne(['App\Phone']);

        $this->assertContains(
            'phone',
            $file->methodNames()
        );
    }

    /** @test */
    public function it_wont_overwrite_already_existing_method()
    {
        $file = LaravelFile::load('app/Models/User.php')
            ->hasOne(['App\Phone'])
            ->hasOne(['App\Phone']);

        $this->assertCount(
            2,
            $file->methodNames()
        );
    }    
}