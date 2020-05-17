<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Endpoints\Laravel\Maker\Unimplemented;
use PHPFileManipulator\Endpoints\PHP\Maker;
use PHPFileManipulator\Support\URI\UriFactory;

class LaravelMaker extends Maker
{
    protected $publishableStubs = [
        // ASSUMED DEFAULTS: namespace + class
        'command'               => Maker\Command::class,
        'model'                 => Maker\Model::class,

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
        $handler = $this->publishableStubs[$method];

        return $handler::make(...$args)->in($this->file)->get();        
    }

    public function model($name)
    {
        $this->uri = UriFactory::make($name); // TODO
        $this->filename = $name;
        $this->namespace = 'Some\App\\Namespaze';
        $this->class = $name;

        $contents = file_get_contents($this->stubPath('model.stub'));
        $contents = str_replace(['DummyNamespace', '___namespace___', '{{ namespace }}'], $this->namespace, $contents);
        $contents = str_replace(['{{ class }}', 'DummyClass', '___class___'], $this->class, $contents);                
        dd($contents);
        return $this->file->fromString($contents)
            ->outputDriver($this->outputDriver());        
    }

    protected function stubPath($name)
    {
        return base_path('vendor/laravel/framework/src/Illuminate/Foundation/Console/stubs/' . $name);
    }    
}