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
     * @return mixed
     */
    public function extends(?string $name = null)
    {
        if ($name === null) {
            return $this->get();
        }

        return $this->set($name);
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->class()
            ->extends
            ->remember('formatted_extends', function ($node) {
                $parts = $node->parts ?? null;
                return $parts ? join('\\', $parts) : null;
            })
            ->recall('formatted_extends')
            ->first();
    }

    protected function set(string $newExtends)
    {
        return $this->file->astQuery()
            ->class()
            ->replaceProperty(
                'extends',
                new \PhpParser\Node\Name($newExtends)
            )
            ->commit()
            ->end()
            ->continue();
    }
}
