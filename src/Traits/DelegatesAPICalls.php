<?php

namespace Ajthinking\PHPFileManipulator\Traits;
use Illuminate\Support\Str;
use BadMethodCallException;

trait DelegatesAPICalls
{
    /**
     * Any call to the file that cant be found as a method on $this are catched here
     * We will try to find a handler to delegate to
     *
     * @param [string] $method
     * @param [array] $args
     * @return mixed
     */
    public function __call($method, $args) {
        $handler = $this->getHandler($method);
        return $handler->$method(...$args);
    }

    /**
     * Find a handler to delegate to
     *
     * @param [string] $method
     * @return [Resource|Template|QueryBuilder]
     */
    private function getHandler($method) {
        $handler = collect([
            $this->getResourceHandler($method),
            $this->getTemplateHandler($method),
            $this->getQueryBuilderHandler($method),
        ])->filter()->first();
    
        if($handler) return $handler;
    
        throw new BadMethodCallException("Could not find a handler for method $method");
    }

    private function getResourceHandler($method)
    {
        $resource = $this->resources()->filter(function($resource) use($method) {
            return preg_match("/^$resource\$/i", $method)
                || preg_match("/^add$resource\$/i", $method)
                || preg_match("/^remove$resource\$/i", $method);
        })->first();

        if($resource) {
            $resourceClass = "Ajthinking\PHPFileManipulator\Resources\\" . Str::studly($resource) . "Resource";
            return new $resourceClass($this);
        };

        return false; // this method could not be handled by a resource
    }
    
    private function getTemplateHandler($method)
    {
        return false;
    }

    private function getQueryBuilderHandler($method)
    {
        return false;
    }    
}