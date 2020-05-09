<?php

namespace PHPFileManipulator;

use PHPFileManipulator\Traits\DelegatesAPICalls;
use PHPFileManipulator\Traits\HasIO;
use PHPFileManipulator\Traits\HasIntermediateDirectives;

class PHPFile
{
    use HasIO;
    use DelegatesAPICalls;
    use HasIntermediateDirectives;

    protected $input;

    protected $output;

    protected $contents;

    protected $fileQueryBuilder = Endpoints\PHP\FileQueryBuilder::class;

    protected $ast;

    protected $initialModificationHash;

    protected $intermediateDirectives = [];

    protected const endpointProviders = [
        // Utillities
        Endpoints\SyntacticSweetener::class,
        Endpoints\PHP\AstQuery::class,
        Endpoints\PHP\ReflectionProxy::class,

        // Resources
        Endpoints\PHP\Property::class,
        Endpoints\PHP\Method::class,
        Endpoints\PHP\MethodNames::class,
        Endpoints\PHP\Namespace_::class,
        Endpoints\PHP\Use_::class,
        Endpoints\PHP\ClassName::class,
        Endpoints\PHP\Extends_::class,
        Endpoints\PHP\Implements_::class,
        Endpoints\PHP\Trait_::class,
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
}