<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;
use PHPFileManipulator\LaravelFile;

class DuplicateEndpointTest extends FileTestCase
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