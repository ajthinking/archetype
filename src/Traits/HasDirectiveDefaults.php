<?php

namespace Archetype\Traits;

use Archetype\Support\Types;

trait HasDirectiveDefaults
{
    public function add($value = Types::NO_VALUE)
    {
        $this->directive('add', true);

        if ($value !== Types::NO_VALUE) {
            $this->directive('addValue', $value);
        }

        return $this;
    }

    public function include($value = Types::NO_VALUE)
    {
        return $this->unique($value = Types::NO_VALUE);
    }

    public function unique($value = Types::NO_VALUE)
    {
        dd("NOT IMPLEMENTED");

        $this->directive('unique', true);

        if ($value !== Types::NO_VALUE) {
            $this->directive('uniqueValue', $value);
        }

        return $this;
    }

    public function remove()
    {
        return $this->directive('remove', true);
    }

    public function clear()
    {
        return $this->directive('clear', true);
    }

    public function empty()
    {
        return $this->directive('empty', true);
    }

    public function full()
    {
        return $this->directive('full', true);
    }

    public function public()
    {
        return $this->directive('flag', 'public');
    }

    public function protected()
    {
        return $this->directive('flag', 'protected');
    }

    public function private()
    {
        return $this->directive('flag', 'private');
    }

    public function static()
    {
        return $this->directive('flag', 'static');
    }

    public function assumeType($value)
    {
        return $this->directive('assumeType', $value);
    }
}
