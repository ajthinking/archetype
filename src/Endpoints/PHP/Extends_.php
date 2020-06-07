<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;

class Extends_ extends EndpointProvider
{
    /**
     * @example Get class extends
     * @source $file->extends()
     * 
     * @example Set class extends
     * @source $file->extends('App\BaseProduct')
     * 
     * @param string $name
     * @return void
     */
    public function extends($name = null)
    {
        if($name === null) return $this->get();

        return $this->set($name);
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->class()
            ->extends
            ->remember('formatted_extends', function($node) {
                $parts = $node->parts ?? null;
                return $parts ? join('\\', $parts) : null;
            })
            ->recall()
            ->pluck('formatted_extends')
            ->first();
    }

    protected function set($newExtends)
    {
        return $this->file->astQuery()
            ->class()
            ->extends
            ->replace(
                new \PhpParser\Node\Name($newExtends)
            )
            ->commit()
            ->end()
            ->continue();
    }    
}