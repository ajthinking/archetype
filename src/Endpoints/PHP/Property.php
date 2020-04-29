<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PhpParser\BuilderHelpers;
use PhpParser\{ConstExprEvaluator, ConstExprEvaluationException};

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

    protected function set($key, $value)
    {
        return $this->file->astQuery()
            ->propertyProperty()
            ->where('name->name', $key)
            ->default
            ->replace(BuilderHelpers::normalizeValue($value))
            ->commit()
            ->end();
    }

    protected function getWithReflection($name)
    {
        return $this->file->getReflection()->getDefaultProperties()[$name];
    }

    protected function getWithParser($name)
    {
        $propertyAST = $this->file->astQuery()
            ->propertyProperty()
            ->where('name->name', $name)
            ->default
            ->get()
            ->first();
        
        return (new ConstExprEvaluator())->evaluateSilently($propertyAST);
        
    }
}