<?php

namespace Archetype;

use Archetype\Support\AST\ASTQueryBuilder;
use Archetype\Traits\DelegatesAPICalls;
use Archetype\Traits\HasDirectives;
use Archetype\Traits\HasDirectiveHandlers;
use Archetype\Traits\HasIO;

class PHPFile
{
    use HasIO;
    use DelegatesAPICalls;
    use HasDirectives;
    use HasDirectiveHandlers;

    protected $input;

    protected $output;

    protected string $contents;

    protected string $fileQueryBuilder = Endpoints\PHP\PHPFileQueryBuilder::class;

	public string $astQueryBuilder = ASTQueryBuilder::class;

    protected $ast;

    protected string $initialModificationHash;

    protected $originalAst;

    protected $tokens;

    protected $lexer;

    protected $directives = [];

    protected const endpointProviders = [
        // Utilities
        Endpoints\PHP\AstQuery::class,
        Endpoints\PHP\Make::class,		
        Endpoints\PHP\ReflectionProxy::class,
        Endpoints\SyntacticSweetener::class,

        // Resources
        Endpoints\PHP\ClassConstant::class,
        Endpoints\PHP\ClassName::class,
        Endpoints\PHP\Extends_::class,
        Endpoints\PHP\Implements_::class,
        Endpoints\PHP\MethodNames::class,
        Endpoints\PHP\Namespace_::class,
        Endpoints\PHP\Property::class,
        Endpoints\PHP\Use_::class,
    ];

    public function __construct(
        string $input = \Archetype\Drivers\FileInput::class,
        string $output = \Archetype\Drivers\FileOutput::class
    ) {
        $this->input = $input;
        $this->output = $output;
    }

    public function endpointProviders()
    {
        return collect(self::endpointProviders)->push(
            $this->fileQueryBuilder
        );
    }
}
