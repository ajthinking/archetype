<?php

namespace Archetype\Endpoints\Laravel;

use Archetype\Endpoints\EndpointProvider;
use Archetype\Support\LaravelStrings;
use Archetype\Support\Snippet;
use Illuminate\Support\Arr;

class BelongsToMany extends EndpointProvider
{
    /**
     * @example Add a belongsToMany relationship method
     * @source $file->belongsToMany('Company')
     */
    public function belongsToMany($targets)
    {
        return $this->add($targets);
    }

    protected function add($targets)
    {
        return $this->file->astQuery()
            ->class()
            ->insertStmts(
                collect(Arr::wrap($targets))->map(function ($target) {
                    return Snippet::___BELONGS_TO_MANY_METHOD___([
                        '___BELONGS_TO_MANY_METHOD___' => LaravelStrings::belongsToManyMethodName($target),
                        '___TARGET_CLASS___' => class_basename($target),
                        '___TARGET_IN_DOC_BLOCK___' => LaravelStrings::belongsToManyDocBlockName($target)
                    ]);
                })->toArray()
            )->commit()
            ->end()
            ->continue();
    }
}
