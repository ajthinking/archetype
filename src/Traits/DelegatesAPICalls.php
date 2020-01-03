<?php

namespace Ajthinking\PHPFileManipulator\Traits;
use Illuminate\Support\Str;
use BadMethodCallException;
use Ajthinking\PHPFileManipulator\BaseResource;
use Ajthinking\PHPFileManipulator\BaseSnippet;

trait DelegatesAPICalls
{
    public function __call($method, $args) {
        // Find handler instance (resource, snippet or this)
        $handler = $this->getHandler($method);
        // setter, getter, adder, remover or other ?
        $requestType = $this->getRequestType($method, $args);
        // dispatch method call to handler
        return $handler->$requestType(...$args);
    }

    private function getRequestType($method, $args)
    {
        foreach($this->resources() as $resource) {
            // exact matches are getters/setters
            if(preg_match("/^$resource\$/i", $method)) {
                return $args ? 'set' : 'get';
            }
            // adders
            if(preg_match("/^add$resource\$/i", $method)) {
                return 'add';
            }
            // removers
            if(preg_match("/^remove$resource\$/i", $method)) {
                return 'remove';
            }            
        }

        if($this->getHandler($method) instanceof BaseSnippet) return 'renderAST';

        throw new BadMethodCallException("Could not resolve method type");
    }

    private function getHandler($method)
    {
        // Resource?
        $resource = $this->resources()->filter(function($resource) use($method) {
            return preg_match("/^$resource\$/i", $method)
                || preg_match("/^add$resource\$/i", $method)
                || preg_match("/^remove$resource\$/i", $method);
        })->first();
        
        if($resource) {
            $resourceClass = "Ajthinking\PHPFileManipulator\Resources\\" . Str::studly($resource) . "Resource";
            return new $resourceClass($this);
        };

        // Snippet?
        $snippet = collect(isset($this->snippets) ? $this->snippets : [])->filter(function($snippet) use($method) {
            return preg_match("/^$snippet\$/i", $method);
        })->first();
        
        if($snippet) {
            $snippetClass = "Ajthinking\PHPFileManipulator\Snippets\\" . Str::studly($snippet) . "Snippet";
            return new $snippetClass($this);
        };

        // Defaults to $this
        return $this;
    }

    private function callOnSelf($method, $args = [])
    {
        $methods = get_class_methods($this);
        if(collect($methods)->contains($method)) return $this->$method($args);

        throw new BadMethodCallException("$method: No such method!");
    }
}