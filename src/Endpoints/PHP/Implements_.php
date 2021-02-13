<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use Illuminate\Support\Arr;

class Implements_ extends EndpointProvider
{
    /**
     * @example Get class implements
     * @source $file->implements()
     *
     * @example Set class implements
     * @source $file->implements(['InterfaceA', 'InterfaceB'])
     *
     * @example Add class implements
     * @source $file->add()->implements('InterfaceC')
     * @return mixed
     */
    public function implements($name = null)
    {
        if ($this->file->directive('add')) {
            return $this->add($name);
        }
        
        if ($name === null) {
            return $this->get();
        }

        return $this->set($name);
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->class()
            ->implements
            ->get()
            ->map(function ($name) {
                return implode('\\', $name->parts);
            })->toArray();
    }

    protected function set($newImplements)
    {
        $newImplements = $this->makeNameObject($newImplements);
        
        return $this->file->astQuery()
            ->class()
            ->replaceProperty('implements', $newImplements)
            ->commit()
            ->end()
            ->continue();
    }
    
    protected function add($newImplements)
    {
        return $this->set(
            array_merge(
                $this->get(),
                Arr::wrap($newImplements)
            )
        );
    }

    protected function makeNameObject($names)
    {
        return collect(Arr::wrap($names))->map(function ($name) {
            return new \PhpParser\Node\Name($name);
        })->toArray();
    }
}
