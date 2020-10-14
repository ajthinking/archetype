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

    /** @testx
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
        

        dd(
            is_dir(
                base_path('/vendor/ajthinking/archetype/tests/.output')
            ),
            is_dir(
                base_path('packages/ajthinking/archetype/tests/.output')
            ),
            is_dir(
                __DIR__ . '/../.output'
            ),
            is_file(
                base_path('/vendor/ajthinking/archetype/tests/.output/app/Models/User.php')
            ),
            is_file(
                base_path('packages/ajthinking/archetype/tests/.output/app/Models/User.php')
            ),
            is_file(
                __DIR__ . '/../.output/app/Models/User.php'
            ),                        
        );

        $this->assertContains(
            'social_security_number',
            LaravelFile::load(base_path('/vendor/ajthinking/archetype/tests/.output/app/Models/User.php'))->hidden()
        );

        $this->assertNotContains(
            'a2',
            LaravelFile::load(base_path('/vendor/ajthinking/archetype/tests/.output/app/Models/User.php'))->hidden()
        );        
    }
}