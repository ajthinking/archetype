<?php

use Archetype\Endpoints\PHP\PHPFileQueryBuilder;
use Archetype\Endpoints\Laravel\LaravelFileQueryBuilder;

class DirectivesTest extends Archetype\Tests\TestCase
{
    /** @test */
    public function it_will_remember_directives_when_chained()
    {
        $file = PHPFile::load('app/Models/User.php')->add()->remove();

        $this->assertEquals(
            ['add' => true, 'remove' => true],
            $file->directives(),
        );
    }
    
    /** @test */
    public function it_will_forget_directives_on_continue()
    {
        $file = PHPFile::load('app/Models/User.php')->add()->remove()->continue();
        $this->assertEmpty(
            $file->directives()
        );
    }
}
