<?php

namespace PHPFileManipulator\Traits;

use BadMethodCallException;

use PHPFileManipulator\Resolvers\QueryBuilderResolver;

trait DelegatesAPICalls
{
    /**
     * Time to delegate! This class is just a gateway.
     */
    public function __call($method, $args) {
        $handler = $this->endpoints()->filter(function($endpoint) use($method, $args) {
            return (new $endpoint($this))->canHandle($method, $args);
        })->first();

        if($handler) return (new $handler($this))->$method(...$args);


        /** if querybuilder */
        $queryBuilder = QueryBuilderResolver::getHandler($this, $method); 
        if($queryBuilder) return $queryBuilder->$method(...$args); 

        throw new BadMethodCallException("Could not find a handler for method $method");
    }   
}