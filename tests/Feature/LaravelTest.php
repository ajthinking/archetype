<?php

class LaravelTest extends Archetype\Tests\TestCase
{
    /** @test */
    public function it_can_run_tests()
    {
        $this->assertTrue(true);
    }
    
    /** @test */
    public function it_is_aware_of_laravel_stuff()
    {
        $this->assertTrue(
            base_path() && app()
        );
    }
}
