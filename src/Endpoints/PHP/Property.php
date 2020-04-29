<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;

class Property extends EndpointProvider
{
    const NO_VALUE_PROVIDED = '___NO_VALUE_PROVIDED___';

    /** NO_VALUE_PROVIDED is used to allow null insertion */
    public function property($key, $value = self::NO_VALUE_PROVIDED)
    {
        return $value == self::NO_VALUE_PROVIDED ? $this->get($key) : $this->set($key, $value);    
    }

    protected function get($key)
    {
        return $this->canUseReflection() ? $this->getWithReflection($key) : $this->getWithParser($key);
    }

    protected function set($key, $value /* danger we might want to save null! */)
    {
        return $this->file;
    }

    protected function getWithReflection($name)
    {
        return $this->file->getReflection()->getDefaultProperties()[$name];
    }

    protected function getWithParser($name)
    {
        $result = $this->file->astQuery()
            ->propertyProperty()
            ->where('name->name', $requestedName);

        dd($result);
    }
}