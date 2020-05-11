<?php

namespace PHPFileManipulator\Tests\Unit\Endpoints;

use PHPFileManipulator\Tests\FileTestCase;

use PHPFile;
use LaravelFile;

class LaravelPropertyTest extends FileTestCase
{
    /** @test */
    public function it_can_retrieve_fillables()
    {
        $this->assertTrue(
            $this->user()->fillable() == ['name', 'email', 'password',]
        );
    }

    /** @test */
    public function it_can_retrieve_hidden()
    {
        $this->assertTrue(
            $this->user()->hidden() == ['password', 'remember_token',]
        );
    }
    
    /** @test */
    public function it_wont_break_if_properties_are_missing()
    {
        $this->assertNull(
            LaravelFile::load('public/index.php')->hidden()
        );
    }

    /** @test */
    public function it_will_assume_array_if_we_are_inserting_on_a_new_hidden_property()
    {
        $hidden = $this->user()
            ->remove()->hidden()
            ->hidden('ghost')->hidden();

        $this->assertEquals(
            ['ghost'],
            $hidden            
        );

        $hidden = $this->user()
            ->remove()->hidden()
            ->hidden(['ghost'])->hidden();

        $this->assertEquals(
            ['ghost'],
            $hidden            
        );
    }
    
    /** @test */
    public function it_can_set_fillables()
    {
        $this->assertEquals(
            $this->user()->fillable(['guns', 'roses'])->fillable(),
            ['guns', 'roses',]
        );
    }

    /** @test */
    public function it_can_add_fillables()
    {
        $this->assertEquals(
            $this->user()
                ->fillable(['guns', 'roses'])
                ->add()->fillable(['metallica'])
                ->fillable(),
            ['guns', 'roses', 'metallica']
        );
    }    
    
    /** @test */
    public function it_can_set_hidden()
    {
        $this->assertEquals(
            $this->user()->hidden(['metallica', 'ozzy'])->hidden(),
            ['metallica', 'ozzy',]
        );
    }
    
    /** @test */
    public function it_can_use_setter_on_associative_arrays()
    {
        $output = $this->user()
            ->casts(['free' => 'bird'])
            ->casts();

        $this->assertEquals([
            'free' => 'bird',
        ], $output);
    }

    /** @test */
    public function it_can_add_to_associative_arrays()
    {
        $output = $this->user()
            ->add()->casts(['free' => 'bird'])
            ->casts();

        $this->assertEquals([
            'email_verified_at' => 'datetime',
            'free' => 'bird',
        ], $output);
    }

    /** @test */
    public function it_can_empty_associative_arrays()
    {
        $output = $this->user()
            ->empty()->casts()
            ->casts();

        $this->assertEquals([], $output);
    }    
}