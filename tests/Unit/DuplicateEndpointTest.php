<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\TestCase;
use LaravelFile;

class DuplicateEndpointTest extends TestCase
{
    /** @test */
    public function there_are_no_duplicated_endpoints()
    {
        $endpoints = (new \PHPFileManipulator\LaravelFile)
            ->endpointProviders()
            ->map(function ($provider) {
                return (new $provider())->getEndpoints();
            })->flatten();

        $this->assertEquals(
            $endpoints->count(),
            $endpoints->unique()->count()
        );
    }  
}