<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PhpParser\BuilderHelpers;
use PhpParser\BuilderFactory;
use PHPFileManipulator\Support\AST\NodeInserter;

class Property extends EndpointProvider
{
    const NO_VALUE_PROVIDED = '___NO_VALUE_PROVIDED___';

    public function property($key, $value = self::NO_VALUE_PROVIDED)
    {
        return $value == self::NO_VALUE_PROVIDED ? $this->get($key) : $this->set($key, $value);    
    }

    protected function get($key)
    {
        return $this->canUseReflection() ? $this->getWithReflection($key) : $this->getWithParser($key);
    }

    protected function getWithReflection($name)
    {
        return $this->file->getReflection()->getDefaultProperties()[$name];
    }

    protected function getWithParser($name)
    {
        return $this->file->astQuery()
            ->class()
            ->propertyProperty()
            ->where('name->name', $name)
            ->default
            ->getEvaluated()
            ->first();
    }    

    protected function set($key, $value)
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
            ->replace(BuilderHelpers::normalizeValue($value))
            ->commit()
            ->end();        
    }

    // protected function create($key, $value)
    // {
    //     // All class statements
    //     $stmts = $this->file->astQuery()->class()->get()->first()->stmts;
    //     // Find the first non TraitUse statement to have a reference for insertion
    //     $indexNode = collect($stmts)->first(function($stmt) {
    //         return !collect(['PhpParser\Node\Stmt\TraitUse'])->contains(get_class($stmt));
    //     });

    //     $newProperty = (new BuilderFactory)->property($key)->setDefault($value)->getNode();
    //     $updatedAST = NodeInserter::insertBefore(
    //         $indexNode->__object_hash ?? null,
    //         $newProperty,
    //         $this->file->ast()
    //     );   

    //     return $this->file->ast($updatedAST);
    // }

    protected function create($key, $value)
    {
        return $this->file->astQuery()
            ->class()
            ->insertStmt(
                (new BuilderFactory)->property($key)->setDefault($value)->getNode()                
            )
            ->commit()
            ->end();
    }
}