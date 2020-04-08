<?php

namespace PHPFileManipulator\Traits;

use BadMethodCallException;

trait DelegatesAPICalls
{
    /**
     * Time to delegate! This class is just a gateway.
     */
    public function __call($method, $args) {
        $handler = $this->endpointProviders()->filter(function($endpoint) use($method, $args) {
            return (new $endpoint($this))->canHandle($method, $args);
        })->first();

        if($handler) return (new $handler($this))->$method(...$args);

        throw new BadMethodCallException("Could not find a handler for method $method");
    }

    public function bootPropertyProxies()
    {
        // Here we want to map all simple API routes onto $this
        // For instance $this->className() should also be fetchable with $this->className.
        // Then we can use $files->pluck('className')
        // Collection::pluck does not allow dynamic values :(
    }

    /**
     * It should be able to list all endpoints...
     */    
    public function listAPI()
    {
        //
    }    
}