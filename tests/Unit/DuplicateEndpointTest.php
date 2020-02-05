<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\TestCase;
use PHPFileManipulator\LaravelFile;

class DuplicateEndpointTest extends TestCase
{
    /** @test */
    public function there_are_no_duplicated_endpoints()
    {
        dd(
            config('dummy')
        );

        $endpoints = (new LaravelFile)
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