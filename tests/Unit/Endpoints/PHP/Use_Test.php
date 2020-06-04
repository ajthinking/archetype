<?php

class Use_Test extends Archetype\Tests\FileTestCase
{
    /** @test */
    public function it_can_retrieve_use_statements()
    {
        // A file with use statements
        $file = PHPFile::load('app/User.php');
        $useStatements = $file->use();
        $expectedUseStatements = collect([
            "Illuminate\Notifications\Notifiable",
            "Illuminate\Contracts\Auth\MustVerifyEmail",
            "Illuminate\Foundation\Auth\User as Authenticatable",
        ]);

        $expectedUseStatements->each(function($expectedUseStatement) use($useStatements){
            $this->assertTrue(
                collect($useStatements)->contains($expectedUseStatement)
            );
        });

        // A file without use statements
        $file = PHPFile::load('public/index.php');
        $useStatements = $file->use();

        $this->assertTrue(
            collect($useStatements)->count() === 0
        );

    }
    
    /** @test */
    public function it_can_add_use_statements_in_a_namespace()
    {
        // on a file with use statements        
        $file = PHPFile::load('app/User.php');

        $useStatements = $file->add()->use(['Add\This'])->use();

        $expectedUseStatements = collect([
            "Illuminate\Notifications\Notifiable",
            "Illuminate\Contracts\Auth\MustVerifyEmail",
            "Illuminate\Foundation\Auth\User as Authenticatable",            
            "Add\This",            
        ]);

        $expectedUseStatements->each(function($expectedUseStatement) use($useStatements){
            $this->assertTrue(
                collect($useStatements)->contains($expectedUseStatement)
            );
        });        
    }

    /** @test */
    public function it_can_add_use_statements_when_not_in_a_namespace()
    {
                
        $file = PHPFile::load('public/index.php');
        
        $useStatements = $file->add()->use(['Add\This'])->use();
        
        $expectedUseStatements = collect([            
            "Add\This",            
        ]);

        $expectedUseStatements->each(function($expectedUseStatement) use($useStatements){
            $this->assertTrue(
                collect($useStatements)->contains($expectedUseStatement)
            );
        });
    }

    /** @test */
    public function it_can_add_use_statements_with_alias()
    {        
        $file = PHPFile::load('public/index.php');
        $useStatements = $file->add()->use(['Add\This as Wow'])->use();
        $expectedUseStatements = collect([            
            "Add\This as Wow",            
        ]);
        
        $expectedUseStatements->each(function($expectedUseStatement) use($useStatements){
            $this->assertTrue(
                collect($useStatements)->contains($expectedUseStatement)
            );
        });
    }    

    /** @test */
    public function it_can_overwrite_use_statements()
    {
        $file = PHPFile::load('app/User.php');

        $useStatements = $file->use(['Only\This'])->use();
        $expectedUseStatements = collect([
            "Only\This",
        ]);

        $this->assertCount(1, collect($useStatements));

        $this->assertEquals(
            $useStatements->first(),
            'Only\This'
        );        
    }
}