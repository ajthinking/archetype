<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;

class MethodNames extends EndpointProvider
{
    /**
     * @example Get class method names
     * @source $file->methodNames()
     */
    public function methodNames()
    {
        return $this->get();
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->classMethod()
            ->name
            ->name
            ->get()
            ->toArray();
    }
}
