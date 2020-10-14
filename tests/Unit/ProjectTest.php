<?php

use Archetype\Facades\Project;

class ProjectTest extends Archetype\Tests\FileTestCase
{    
    /** @test */
    public function it_follows_the_builder_pattern()
    {
        // MANY OF THESE METHODS ARE JUST PLACEHOLDERS RETURNING $this!        
        $project = Project::current()
            ->gitInit()
            ->gitCommit('Initial commit')
            ->useSqlite()
            ->composerInstall()
            ->yarn()
            ->migrate()
            ->extend()->schema("
                User
                employee_number integer unique
            
                Bike
                brand
                size integer
                serial string nullable
            
                Shop
                name
                location
                
            ")
            ->build()
            ->migrate()
            ->test()
            ->gitCommit('Initial scaffolding');

        // It didnt crash!
        $this->assertTrue(true);
    }

    /** @test
     * @group progress
    */
    public function it_can_build()
    {
        Project::current()->extend()
            ->schema("
                User
                social_security_number hidden
                a2 visible
            ")->build();

        $this->assertContains(
            'social_security_number',
            LaravelFile::load(__DIR__ . '/../.output/app/Models/User.php')->hidden()
        );

        $this->assertNotContains(
            'a2',
            LaravelFile::load(__DIR__ . '/../.output/app/Models/User.php')->hidden()
        );        
    }
}