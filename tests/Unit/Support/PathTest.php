<?php

use Archetype\Support\Path;

class PathTest extends Archetype\Tests\FileTestCase
{
    /** @test */
    public function it_can_create_paths_with_explicit_default_root()
    {
        $relative = Path::make('app/Models/User.php')->withDefaultRoot(base_path())->full();
        $expected = base_path('app/Models/User.php');
        $this->assertEquals($expected, $relative);

        $absolute = Path::make('/app/Models/User.php')->withDefaultRoot(base_path())->full();
        $expected = '/app/Models/User.php';

        $this->assertEquals($expected, $absolute);
    }

    /** @test */
    public function it_can_create_paths_with_assumed_root()
    {
        $expected = '/app/Models/User.php';
        $relative = Path::make('app/Models/User.php')->full();
        $absolute = Path::make('/app/Models/User.php')->full();
        $this->assertEquals($expected, $relative);
        $this->assertEquals($expected, $absolute);
    }    
}
