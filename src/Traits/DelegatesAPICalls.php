<?php

namespace PHPFileManipulator\Traits;

use BadMethodCallException;

use PHPFileManipulator\Resolvers\ResourceResolver;
use PHPFileManipulator\Resolvers\TemplateResolver;
use PHPFileManipulator\Resolvers\QueryBuilderResolver;

trait DelegatesAPICalls
{
    /**
     * This method is called when no matching method is found on $this
     * Time to delegate!
     */
    public function __call($method, $args) {
        /** if resource */
        $resource = ResourceResolver::getHandler($this, $method);
        if($resource) return $resource->$method(...$args);

        /** if template */
        if(TemplateResolver::canHandle($this, $method)) return $this->fromTemplate($method, ...$args); 

        /** if querybuilder */
        $queryBuilder = QueryBuilderResolver::getHandler($this, $method); 
        if($queryBuilder) return $queryBuilder->$method(...$args); 

        throw new BadMethodCallException("Could not find a handler for method $method");
    } 
}