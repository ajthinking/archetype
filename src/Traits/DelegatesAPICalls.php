<?php

namespace PHPFileManipulator\Traits;

use BadMethodCallException;

use PHPFileManipulator\Resolvers\TemplateResolver;
use PHPFileManipulator\Resolvers\QueryBuilderResolver;

trait DelegatesAPICalls
{
    /**
     * Time to delegate!
     */
    public function __call($method, $args) {
        $handler = $this->endpoints()->filter(function($endpoint) use($method, $args) {
            return (new $endpoint($this))->canHandle($method, $args);
        })->first();

        if($handler) {
            return (new $handler($this))->$method(...$args);
        }

        /** if querybuilder */
        $queryBuilder = QueryBuilderResolver::getHandler($this, $method); 
        if($queryBuilder) return $queryBuilder->$method(...$args); 

        throw new BadMethodCallException("Could not find a handler for method $method");
    }
    
    public function getAPIMap()
    {
        return $this->endpoints()->map(function($endpoint) {
            return collect($endpoint::aliases())->flatMap(function($alias) use($endpoint) {
                return [$alias => $endpoint];
            });
        })->collapse();
    }    
}