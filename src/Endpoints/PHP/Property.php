<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PhpParser\BuilderHelpers;
use PhpParser\BuilderFactory;
use PHPFileManipulator\Support\AST\NodeInserter;
use PHPFileManipulator\Support\AST\ASTQueryBuilder;
use PHPFileManipulator\Support\Types;

class Property extends EndpointProvider
{
    // RESPONDS TO empty, remove, add **************************************************

    // TODO

    // Incremental arrays
    // ->empty()->fillable() : sets $fillable = [];
    // ->clear()->fillable() : sets $fillable;    
    // ->add()->fillable('cool') : pushes 'cool' to end of array

    // Strings
    // ->empty()->tableName() : NO EFFECT;
    // ->clear()->tableName() : sets $tableName;
    // ->add()->tableName('cool') : NO EFFECT

    // Empty

    // Null

    // Associative arrays


    public function property($key, $value = Types::NO_VALUE)
    {
        // remove?
        if($this->file->directive('remove')) return $this->remove($key);

        // clear?
        if($this->file->directive('clear')) return $this->clear($key);        

        // get?
        if($value === Types::NO_VALUE) return $this->get($key);

        // set
        return $this->set($key, $value);    
    }

    public function setProperty($key, $value = Types::NO_VALUE)
    {
        return $this->set($key, $value);    
    }

    protected function remove($key)
    {
        return $this->file->astQuery()
            ->class()
            ->property()
            ->whereASTQuery(function($query) use($key) {
                return $query->propertyProperty()
                    ->where('name->name', $key)
                    ->get()->isNotEmpty();
            })
            ->remove()
            ->commit()
            ->end()
            ->continue();
    }

    protected function clear($key)
    {
        return $this->setProperty($key);
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

    protected function set($key, $value = Types::NO_VALUE)
    {
        $propertyExists = $this->file->astQuery()
            ->class()
            ->propertyProperty()
            ->where('name->name', $key)
            ->get()->isNotEmpty();

        return $propertyExists ? $this->update($key, $value) : $this->create($key, $value);
    }

    protected function create($key, $value)
    {
        return $this->file->astQuery()
            ->class()
            ->insertStmt($this->makeProperty($key, $value))
            ->commit()
            ->end()
            ->continue();
    }    

    protected function update($key, $value)
    {
        return $this->file->astQuery()
            ->class()
            ->property()
            ->whereASTQuery(function($query) use($key) {
                return $query->propertyProperty()
                    ->where('name->name', $key)
                    ->get()->isNotEmpty();
            })
            ->replace(function($queryNode) {
                $node = $queryNode->results;
                $node->flags = $this->flagCode();
                return $node;
            })
            ->propertyProperty()
            ->where('name->name', $key)
            ->default
            ->replace(
                $value == Types::NO_VALUE ? null : BuilderHelpers::normalizeValue($value)
            )
            ->commit()
            ->end()
            ->continue();                    
    }

    protected function makeProperty($key, $value)
    {
        $property = (new BuilderFactory)->property($key);
        $property = $property->{'make' . $this->flag()}();

        if($value !== Types::NO_VALUE) {
            $property = $property->setDefault(
                BuilderHelpers::normalizeValue($value)
            );
        }

        return $property->getNode();
    }

    protected function flag()
    {
        return $this->file->directive('flag') ?? 'protected';
    }

    protected function flagCode()
    {
        return [
            'public'    =>  1,
            'protected' =>  2,
            'private'   =>  4,
            'static'    =>  8,
            'abstract'  => 16,
            'final'     => 32,        
        ][$this->flag()];
    }
}