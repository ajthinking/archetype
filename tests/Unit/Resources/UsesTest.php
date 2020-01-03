<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit\Resources;

use Ajthinking\PHPFileManipulator\Tests\TestCase;

class UsesTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_use_statements()
    {
        // A file with use statements
        $file = $this->userFile();
        $useStatements = $file->uses();
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
        $file = $this->routesFile();
        $useStatements = $file->uses();

        $this->assertTrue(
            collect($useStatements)->count() === 0
        );

    }
    
    /** @test */
    public function it_can_add_use_statements_in_a_namespace()
    {
        // on a file with use statements        
        $file = $this->userFile();

        $useStatements = $file->addUses(['Add\This'])->uses();

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
                
        $file = $this->routesFile();
        
        $useStatements = $file->addUses(['Add\This'])->uses();
        
        $expectedUseStatements = collect([            
            "Add\This",            
        ]);

        $expectedUseStatements->each(function($expectedUseStatement) use($useStatements){
            $this->assertTrue(
                collect($useStatements)->contains($expectedUseStatement)
            );
        });
    }

    /** @wip-test */
    public function it_can_add_use_statements_with_alias()
    {        
        $file = $this->routesFile();
        $useStatements = $file->addUses(['Add\This as Wow'])->uses();
        $expectedUseStatements = collect([            
            "Add\This as Wow",            
        ]);
        
        $expectedUseStatements->each(function($expectedUseStatement) use($useStatements){
            $this->assertTrue(
                collect($useStatements)->contains($expectedUseStatement)
            );
        });
    }    


    /** @wip-test */
    public function it_can_overwrite_use_statements()
    {
        $file = $this->userFile();

        $useStatements = $file->uses(['Only\This'])->uses();
        $expectedUseStatements = collect([
            "Only\This",
        ]);

        $this->assertTrue(
            collect($useStatements)->count() == 1
        );

        $this->assertTrue(
            $useStatements[0] == 'Only\This'
        );        
    }
}