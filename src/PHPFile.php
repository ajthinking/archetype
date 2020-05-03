<?php

namespace PHPFileManipulator;

use PHPFileManipulator\Traits\DelegatesAPICalls;
use PHPFileManipulator\Traits\HasIO;
use PHPFileManipulator\Traits\HasIntermediateDirectives;
use PHPFileManipulator\Traits\HasSyntacticSugar;

class PHPFile
{
    use HasIO;
    use DelegatesAPICalls;
    use HasIntermediateDirectives;
    use HasSyntacticSugar;

    protected $input;

    protected $output;

    protected $contents;

    protected $fileQueryBuilder = Endpoints\PHP\FileQueryBuilder::class;

    protected $ast;

    protected $initialModificationHash;

    protected $intermediateDirectives = [];

    protected const endpointProviders = [
        // Utillities
        Endpoints\PHP\AstQuery::class,
        Endpoints\PHP\ReflectionProxy::class,

        // Resources
        Endpoints\PHP\Property::class,
        Endpoints\PHP\NamespaceResource::class,
        Endpoints\PHP\Uses::class,
        Endpoints\PHP\ClassName::class,
        Endpoints\PHP\ClassExtends::class,
        Endpoints\PHP\ClassImplements::class,
        Endpoints\PHP\ClassMethods::class,
        Endpoints\PHP\ClassMethodNames::class,
    ];

    public function endpointProviders() {
        return collect(self::endpointProviders)->push(
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