<?php

namespace Archetype\Endpoints\Laravel;

use Archetype\Endpoints\EndpointProvider;
use Archetype\Support\Snippet;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class BelongsTo extends EndpointProvider
{
    /**
     * @example Add a belongsTo relationship method
     * @source $file->belongsTo('Company')
     */
    public function belongsTo($targets)
    {
        return $this->add($targets);
    }

    protected function add($targets)
    {
        $targets = Arr::wrap($targets);

        $this->file->add()->classMethod(
            collect($targets)->map(function($target) {
                return Snippet::___BELONGS_TO_METHOD___([
                    '___BELONGS_TO_METHOD___' => Str::belongsToMethodName($target),
                    '___TARGET_CLASS___' => class_basename($target),
                    '___TARGET_IN_DOC_BLOCK___' => Str::belongsToDocBlockName($target)
                ]);
            })->toArray()
        );

        return $this->file->continue();
    }
}