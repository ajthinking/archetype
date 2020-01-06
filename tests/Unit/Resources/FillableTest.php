<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit\Resources;

use Ajthinking\PHPFileManipulator\Tests\TestCase;

class FillableTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_fillables()
    {
        $this->assertTrue(
            $this->laravelUserFile()->fillable() == ['name', 'email', 'password',]
        );
    }
}