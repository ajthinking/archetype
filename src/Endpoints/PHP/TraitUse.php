<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use Exception;

class TraitUse extends EndpointProvider
{
    /**
     * @example Get class traits
     * @source $file->trait()
     *
     * @example Set class traits
     * @source INCOMPLETE
     *
     * @example Add class traits
     * @source INCOMPLETE
     *
     * @param string $value
     * @return mixed
     */
    public function trait($value = null)
    {
        if ($value === null) {
            return $this->get();
        }

		throw new Exception("Trait endpoint only supports get");
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->traitUse()
            ->remember('formatted_traits', function ($node) {
                return collect($node->traits)->map(function ($trait) {
                    return join('\\', $trait->parts);
                })->toArray();
            })
            ->recall('formatted_traits')
            ->first();
    }
}
