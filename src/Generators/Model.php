<?php

namespace Archetype\Generators;

use Archetype\Facades\LaravelFile;
use Archetype\Generators\BaseGenerator;

class Model extends BaseGenerator
{
    public function qualifies()
    {
        return $this->schema->entities->where('name', '!=', 'User')->isNotEmpty();
    }

    public function build()
    {
        $this->files = $this->findOrCreateModelFiles();
        //$this->setHidden();
    }

    protected function setHidden()
    {
        // Get hidden attributes
        $hiddens = $this->userEntity()
            ->attributes->filter->hasDirective('hidden')
            ->map->name->toArray();
        
        // Set hidden
        $this->file->add()->hidden($hiddens)->save();
    }

    protected function userEntity()
    {
        return $this->schema->entities->where('name', 'User')->first();
    }

    protected function findOrCreateModelFiles()
    {
        return LaravelFile::models()->exceptUser()->where(
            'className',
            'in',
            $this->schema->entities->map->name
        )->get();
    }
}
