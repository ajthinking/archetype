<?php

namespace Archetype\Traits;

use BadMethodCallException;

trait DelegatesAPICalls
{
    public function __call($method, $args)
    {
        $handler = $this->endpointProviders()->filter(function ($endpoint) use ($method, $args) {
            return (new $endpoint($this))->canHandle($method, $args);
        })->first();

        if ($handler) {
            return (new $handler($this))->$method(...$args);
        }

        throw new BadMethodCallException("Could not find a handler for method $method");
    }
}
