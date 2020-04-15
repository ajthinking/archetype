<?php

namespace PHPFileManipulator;

use PHPFileManipulator\Traits\ManagesFileMetadata;
use PHPFileManipulator\Traits\DelegatesAPICalls;
use PHPFileManipulator\Traits\HasIO;

class PHPFile
{
    use HasIO;
    use DelegatesAPICalls;
    use ManagesFileMetadata;
    
    protected $contents;

    protected $ast;

    protected $input;

    protected $output;

    protected $fileQueryBuilder = Endpoints\PHP\FileQueryBuilder::class;

    protected $endpointProviders = [
        // Utillities
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
        return collect((new self)->endpointProviders)->push(
            $this->fileQueryBuilder
        );
    }

    public function templates() {
        return collect(
            //
        );
    }
    
    public function tags()
    {
        return [
            //
        ];
    }   
}