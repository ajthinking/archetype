<?php

namespace Archetype\Traits;

use Archetype\Support\Types;

trait HasDirectiveHandlers
{
    public function directives($directives = null)
    {
        if (!$directives) {
            return $this->directives;
        }

        $this->directives = $directives;

        return $this;
    }

    public function directive($key, $value = Types::NO_VALUE)
    {
        if ($value === Types::NO_VALUE) {
            return $this->directives[$key] ?? null;
        }

        $this->directives[$key] = $value;

        return $this;
    }

    public function continue()
    {
        $this->directives = [];

        return $this;
    }
}
