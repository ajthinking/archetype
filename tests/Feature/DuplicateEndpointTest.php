<?php

use Archetype\LaravelFile;

class DuplicateEndpointTest extends Archetype\Tests\FileTestCase
{
    /** @test */
    public function there_are_no_duplicated_endpoints()
    {   
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