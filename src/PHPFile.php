<?php

namespace Archetype;

use Archetype\Traits\DelegatesAPICalls;
use Archetype\Traits\HasDirectiveDefaults;
use Archetype\Traits\HasDirectiveHandlers;
use Archetype\Traits\HasIO;

class PHPFile
{
    use HasIO;
    use DelegatesAPICalls;
    use HasDirectiveDefaults;
    use HasDirectiveHandlers;

    protected $input;

    protected $output;

    protected $contents;

    protected $fileQueryBuilder = Endpoints\PHP\PHPFileQueryBuilder::class;

    protected $ast;

    protected $initialModificationHash;

    protected $directives = [];

    protected const endpointProviders = [
        // Utilities
        Endpoints\SyntacticSweetener::class,
        Endpoints\PHP\AstQuery::class,
        Endpoints\PHP\ReflectionProxy::class,

        // Resources
        Endpoints\PHP\Maker::class,
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
}
