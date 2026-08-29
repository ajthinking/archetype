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
        return collect(['class', 'enum'])->flatMap(function ($construct) {
            return $this->file->astQuery()
                ->$construct()
                ->implements
                ->get()
                ->map(fn ($node) => $node->name);
        })->toArray();
    }

    /**
     * Classes and enums, because those are the two constructs that implement.
     * An interface extends rather than implements, and giving it an `implements`
     * would produce something PHP cannot parse.
     */
    protected function set($newImplements)
    {
        $newImplements = $this->makeNameObject($newImplements);

        foreach (['class', 'enum'] as $construct) {
            $this->file->astQuery()
                ->$construct()
                ->replaceProperty('implements', $newImplements)
                ->commit()
                ->end();
        }

        return $this->file->continue();
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
