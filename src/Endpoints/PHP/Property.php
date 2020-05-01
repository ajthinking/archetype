<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PhpParser\BuilderHelpers;
use PhpParser\BuilderFactory;
use PHPFileManipulator\Support\AST\NodeInserter;

class Property extends EndpointProvider
{
    public function property($key, $value = self::NO_VALUE_PROVIDED)
    {
        return $value == self::NO_VALUE_PROVIDED ? $this->get($key) : $this->set($key, $value);    
    }

    public function setProperty($key, $value = self::NO_VALUE_PROVIDED)
    {
        return $this->set($key, $value);    
    }

    protected function get($key)
    {
        return $this->canUseReflection() ? $this->getWithReflection($key) : $this->getWithParser($key);
    }

    protected function getWithReflection($name)
    {
        return $this->file->getReflection()->getDefaultProperties()[$name];
    }    

    protected function getWithParser($key)
    {
        return $this->file->astQuery()
            ->class()
            ->propertyProperty()
            ->where('name->name', $key)
            ->default
            ->getEvaluated()
            ->first();
    }

    protected function set($key, $value = self::NO_VALUE_PROVIDED)
    {
        $propertyExists = $this->file->astQuery()
            ->class()
            ->propertyProperty()
            ->where('name->name', $key)
            ->get()->isNotEmpty();

        return $propertyExists ? $this->update($key, $value) : $this->create($key, $value);
    }

    protected function update($key, $value)
    {
        return $this->file->astQuery()
            ->class()
            ->propertyProperty()
            ->where('name->name', $key)
            ->default
            ->replace(
                $value == self::NO_VALUE_PROVIDED ? null : BuilderHelpers::normalizeValue($value)
            )
            ->commit()
            ->end()
            ->continue();        
    }

    protected function create($key, $value)
    {
        $property = (new BuilderFactory)->property($key);
        $property = $property->{'make' . $this->flag()}();

        if($value !== self::NO_VALUE_PROVIDED) {
            $property = $property->setDefault(
                BuilderHelpers::normalizeValue($value)
            );
        }

        return $this->file->astQuery()
            ->class()
            ->insertStmt($property->getNode())
            ->commit()
            ->end()
            ->continue();
    }

    protected function flag()
    {
        return $this->file->directive('flag') ?? 'protected';
    }
}