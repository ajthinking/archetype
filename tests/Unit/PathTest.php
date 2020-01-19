<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\TestCase;

use PHPFileManipulator\Support\Path;

/**
 * @group path
 */
class PathTest extends TestCase
{
    /** @test */
    public function it_can_create_paths_with_explicit_root()
    {
        $expected = base_path('app/User.php');
        $relative = Path::make('app/User.php')->withRoot(base_path())->full();
        $absolute = Path::make('/app/User.php')->withRoot(base_path())->full();
        $this->assertEquals($expected, $relative);
        $this->assertEquals($expected, $absolute);
    }

    /** @test */
    public function it_can_create_paths_with_assumed_root()
    {
        $expected = '/app/User.php';
        $relative = Path::make('app/User.php')->full();
        $absolute = Path::make('/app/User.php')->full();
        $this->assertEquals($expected, $relative);
        $this->assertEquals($expected, $absolute);
    }    
}
