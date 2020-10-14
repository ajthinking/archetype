<?php

namespace Archetype\Generators;

use Archetype\Facades\LaravelFile;
use Archetype\Generators\BaseGenerator;
use Archetype\Schema\SimpleSchema\SimpleSchema;

class User extends BaseGenerator
{
    public function qualifies()
    {
        return $this->schema->entites->where('name', 'User')->isNotEmpty();
    }

    public function build()
    {
        $this->file = $this->findOrCreateUserFile();

        $this->setHidden();
    }

    protected function setHidden()
    {
        // Get hidden attribues
        $hiddens = $this->userEntity()
            ->attributes->filter->hasDirective('hidden')
            ->map->name->toArray();
        
        // Set hidden
        $this->file->add()->hidden($hiddens)->save();
    }

    protected function userEntity()
    {
        return $this->schema->entites->where('name', 'User')->first();
    }

    protected function findOrCreateUserFile()
    {
        return LaravelFile::user();
    }
}