<?php

namespace PHPFileManipulator\Tests;

use PHPFileManipulator\Tests\TestCase;

class UniqueTest extends TestCase
{
    public function setUp() : void
    {
        exit("Hey, im dying here, inside child!");
    }

    /** @test */
    public function it_will_call_setup_first()
    {
        $this->assertTrue(false);
    }
} 