<?php

namespace PHPFileManipulator;

use PHPFileManipulator\Traits\DelegatesAPICalls;

class PHPFile
{
    use DelegatesAPICalls;

    protected $endpoint_providers = [

        // Utillities
        Endpoints\PHP\IO::class,
        Endpoints\PHP\Template::class,
        Endpoints\PHP\FileQueryBuilder::class,
        Endpoints\PHP\AstQuery::class,

        // Resources
        Endpoints\PHP\NamespaceResource::class,
        Endpoints\PHP\Uses::class,
        Endpoints\PHP\ClassName::class,
        Endpoints\PHP\ClassExtends::class,
        Endpoints\PHP\ClassImplements::class,
        Endpoints\PHP\ClassMethods::class,
        Endpoints\PHP\ClassMethodNames::class,            
    ];

    public function endpointProviders() {
        return collect((new self)->endpoint_providers);
    }

    public function templates() {
        return collect(
            //
        );
    }    
}