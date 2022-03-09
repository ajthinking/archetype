<?php

namespace Archetype\Endpoints\Laravel;

use Archetype\Endpoints\EndpointProvider;
use Archetype\Support\LaravelStrings;
use Archetype\Support\Snippet;
use Illuminate\Support\Arr;

class HasMany extends EndpointProvider
{
    /**
     * @example Add a hasMany relationship method
     * @source $file->hasMany('Company')
     */
    public function hasMany($targets)
    {
        return $this->add($targets);
    }

    protected function add($targets)
    {
        return $this->file->astQuery()
            ->class()
            ->insertStmts(
                collect(Arr::wrap($targets))->map(function ($target) {
                    return Snippet::___HAS_MANY_METHOD___([
                        '___HAS_MANY_METHOD___' => LaravelStrings::hasManyMethodName($target),
                        '___TARGET_CLASS___' => class_basename($target),
                        '___TARGET_IN_DOC_BLOCK___' => LaravelStrings::hasManyDocBlockName($target)
                    ]);
                })->toArray()
            )->commit()
            ->end()
            ->continue();
    }
}
