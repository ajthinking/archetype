<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use PhpParser\BuilderFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Use_ extends EndpointProvider
{
    /**
     * @example Get file uses
     * @source $file->use()
     *
     * @example Set file uses
     * @source $file->use(['ClassA', 'classB'])
     *
     * @example Use with alias
     * @source $file->use('ClassA as Ajthinking')
     *
     * @example Add file uses
     * @source $file->add()->use('AdditionalClass')
     *
     * @return mixed
     */
    public function use($value = null)
    {
        if ($this->file->directive('add')) {
            return $this->add($value);
        }

        if ($value === null) {
            return $this->get();
        }
        
        return $this->set($value);
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->use()
            ->uses
            ->get()
            ->map(function ($useStatement) {
                $base = join('\\', $useStatement->name->parts);
                return $base . ($useStatement->alias ? ' as ' . $useStatement->alias : '');
            })->toArray();
    }

    protected function set($newUseStatements)
    {
        $this->file->astQuery()
            ->use()
            ->remove()
            ->commit();

        return $this->add($newUseStatements);
    }

    protected function add($newUseStatements)
    {
        collect(Arr::wrap($newUseStatements))->each(function ($name) {
            $this->file->astQuery()
            ->insertStmt(
                $this->useStatement($name)
            )
            ->commit();
        });

        return $this->file->continue();
    }

    protected function useStatement(string $signature)
    {
        $parts = Str::of($signature)->explode(' as ');
        $name = $parts->first();
        $statement = (new BuilderFactory)->use($name);
        
        if ($parts->last() != $parts->first()) {
            $statement = $statement->as($parts->last());
        }

        return $statement->getNode();
    }
}
