<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use Illuminate\Support\Arr;

class Method extends EndpointProvider
{
    public function classMethod($methods = null)
    {
        // set? | no distinction between add/set
        if($this->file->directive('add') || $methods) return $this->set($methods);

        // get?
        return $this->get();
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->method()
            ->get()
            ->toArray();
    }

    protected function set($methods)
    {
        // no value but has value from intermidiate add directive?
        if(!$methods && $this->file->directive('addValue')) {
            $methods = $this->file->directive('addValue');
        }

        $methods = Arr::wrap($methods);
        
        collect($methods)->each(function($method) {
            $this->setOne($method);
        });

        return $this->file->continue();
    }

    protected function setOne($method)
    {
        // replace existing
        $replaced = $this->file->astQuery()
            ->classMethod()
            ->where('name->name', $method->name->name)
            ->replace($method)
            ->commit()
            ->first();

        if($replaced) return;

        // insert new
        $this->file->astQuery()
            ->class()
            ->insertStmt($method)
            ->commit();
    }
}