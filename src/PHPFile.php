<?php

namespace PHPFileManipulator;

use PHPFileManipulator\Traits\DelegatesAPICalls;

class PHPFile
{
    use DelegatesAPICalls;

    protected $fileQueryBuilder = Endpoints\PHP\FileQueryBuilder::class;

    protected $endpoint_providers = [
        // Utillities
        Endpoints\PHP\IO::class,
        Endpoints\PHP\AstQuery::class,
        Endpoints\PHP\ReflectionProxy::class,

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
        return collect((new self)->endpoint_providers)->push(
            $this->fileQueryBuilder
        );
    }

    public function templates() {
        return collect(
            //
        );
    }    
}