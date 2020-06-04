<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;

class TraitUse extends EndpointProvider
{
    public function trait($value = null)
    {
        if($value === null) return $this->get();

        return $this->set($value);
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->traitUse()
            ->remember('formatted_traits', function($node) {
                return collect($node->traits)->map(function($trait) {
                    return join('\\', $trait->parts);
                })->toArray();
            })
            ->recall()
            ->pluck('formatted_traits')
            ->first();
    }

    protected function add($value)
    {
        return $this->file->continue();
    }

    protected function set($value)
    {
        return $this->file->continue();
    }
}