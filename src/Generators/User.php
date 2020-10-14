<?php

namespace Archetype\Generators;

use Archetype\Facades\LaravelFile;
use Archetype\Generators\BaseGenerator;
use Archetype\Schema\SimpleSchema\SimpleSchema;

class User extends BaseGenerator
{
    public function qualifies()
    {
        return (boolean) collect($this->schema->entites)->where('name', 'User')->first();
    }

    public function build()
    {
        $this->setHidden();
    }

    protected function setHidden()
    {
        $hiddens = collect($this->userEntity()->attributes)->filter(function($attribute) {
            return collect($attribute->directives)->contains('hidden');
        })->map->name->toArray();
        
        LaravelFile::user()
            ->add()->hidden($hiddens)
            ->save();
    }

    protected function userEntity()
    {
        return collect($this->schema->entites)->where('name', 'User')->first();
    }
}