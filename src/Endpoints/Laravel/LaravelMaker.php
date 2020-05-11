<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Endpoints\Laravel\Maker\Unimplemented;


class LaravelMaker extends EndpointProvider
{
    protected $publishableStubs = [
        // ASSUMED DEFAULTS: namespace + class
        'console'               => Unimplemented::class, // command
        'controller.api'        => Unimplemented::class, // rootNamespace
        'controller.invokable'  => Unimplemented::class, // rootNamespace
        'controller.model.api'  => Unimplemented::class, // rootNamespace, namespacedModel, model, modelVariable
        'controller.model'      => Unimplemented::class, // rootNamespace, namespacedModel, model, modelVariable
        'controller.nested.api' => Unimplemented::class, // rootNamespace, namespacedModel, model, modelVariable, namespacedParentModel, parentModel, parentModelVariable
        'controller.nested'     => Unimplemented::class, // rootNamespace, namespacedModel, model, modelVariable, namespacedParentModel, parentModel, parentModelVariable
        'controller.plain'      => Unimplemented::class, // rootNamespace
        'controller'            => Unimplemented::class, // rootNamespace
        'factory'               => Unimplemented::class, // NO class // NO namespace // namespacedModel, model
        'job.queued'            => Unimplemented::class,
        'job'                   => Unimplemented::class,
        'middleware'            => Unimplemented::class,
        'migration.create'      => Unimplemented::class, // NO namespace // table
        'migration'             => Unimplemented::class, // NO namespace
        'migration.update'      => Unimplemented::class, // NO namespace // table
        'model.pivot'           => Unimplemented::class,
        'model'                 => Unimplemented::class,
        'policy.plain'          => Unimplemented::class, // namespacedUserModel
        'policy'                => Unimplemented::class, // namespacedModel, namespacedUserModel, user, modelVariable, model
        'request'               => Unimplemented::class,
        'rule'                  => Unimplemented::class,
        'seeder'                => Unimplemented::class, // NO NAMESPACE
        'test'                  => Unimplemented::class,
        'test.unit'             => Unimplemented::class,
    ];

    protected function getHandlerMethod($signature, $args)
    {
        return isset($this->publishableStubs[$signature]) ? $signature : false;
    }

    public function getEndpoints()
    {
        return collect($this->publishableStubs)->keys()->toArray();
    }

    public function __call($method, $args)
    {
        //$handler = Class_::class;
        $handler = $this->publishableStubs[$method];

        return $handler::make(...$args)->in($this->file)->get();        
    }

    protected function stubs()
    {
        // HARDCODED STUB
        // CONFIGURED STUB
        // STUB IN /stubs
        // STUB IN FRAMEWORK
    }
}