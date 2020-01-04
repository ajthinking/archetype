<?php

namespace Ajthinking\PHPFileManipulator\Traits;

use Illuminate\Support\Str;
use BadMethodCallException;
use Ajthinking\PHPFileManipulator\Template;

use Ajthinking\PHPFileManipulator\Resolvers\ResourceResolver;
use Ajthinking\PHPFileManipulator\Resolvers\TemplateResolver;
use Ajthinking\PHPFileManipulator\Resolvers\QueryBuilderResolver;

trait DelegatesAPICalls
{
    public function __call($method, $args) {
        /** if resource */
        $resource = ResourceResolver::getHandler($this, $method);
        if($resource) return $resource->$method(...$args);

        /** if template */
        if(TemplateResolver::canHandle($this, $method)) return $this->fromTemplate($method); 

        /** if querybuilder */
        if(QueryBuilderResolver::canHandle($this, $method)) return $this->$method(...$args); 

        throw new BadMethodCallException("Could not find a handler for method $method");
    } 
}